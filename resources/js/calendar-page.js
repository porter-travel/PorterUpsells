const rootElement = document.getElementById('calendar-page-root');

const dataElement = document.getElementById('calendar-page-data');
let initialData = { events: [], products: [], defaultDate: new Date().toISOString() };

if (dataElement && dataElement.textContent) {
    try {
        initialData = JSON.parse(dataElement.textContent);
    } catch (error) {
        console.error('Failed to parse calendar data payload', error);
    }
}

const VIEW_MONTH = 'month';
const VIEW_WEEK = 'week';
const VIEW_DAY = 'day';

const pad = (value) => String(value).padStart(2, '0');

const escapeHtml = (value) => String(value ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');

const formatDateTimeLocal = (date) => {
    if (!(date instanceof Date) || Number.isNaN(date.valueOf())) {
        return '';
    }
    return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
};

const toDate = (value, fallback = null) => {
    if (value instanceof Date) {
        return new Date(value.getTime());
    }

    if (typeof value === 'number') {
        const parsedNumber = new Date(value);
        if (!Number.isNaN(parsedNumber.valueOf())) {
            return parsedNumber;
        }
    }

    if (typeof value === 'string') {
        const parsedString = new Date(value);
        if (!Number.isNaN(parsedString.valueOf())) {
            return parsedString;
        }
    }

    if (fallback !== null) {
        return toDate(fallback, null);
    }

    return new Date(Number.NaN);
};

const startOfWeek = (date) => {
    const result = new Date(date);
    const day = (result.getDay() + 6) % 7; // Monday as start of the week
    result.setDate(result.getDate() - day);
    result.setHours(0, 0, 0, 0);
    return result;
};

const addDays = (date, amount) => {
    const result = new Date(date);
    result.setDate(result.getDate() + amount);
    return result;
};

const addMonths = (date, amount) => {
    const result = new Date(date);
    result.setMonth(result.getMonth() + amount);
    return result;
};

const addWeeks = (date, amount) => addDays(date, amount * 7);

const sameDay = (a, b) =>
    a.getFullYear() === b.getFullYear() && a.getMonth() === b.getMonth() && a.getDate() === b.getDate();

const dayLabel = (date) => {
    return `${date.toLocaleDateString(undefined, { weekday: 'short' })} ${pad(date.getDate())}/${pad(
        date.getMonth() + 1,
    )}`;
};

const compareByStart = (a, b) => a.start.getTime() - b.start.getTime();

class CalendarPageApp {
    constructor(root, data) {
        this.root = root;
        this.products = (data.products ?? []).map((product) => ({
            id: String(product.id ?? ''),
            name: product.name ?? 'Calendar Product',
            capacity:
                product.capacity !== null && product.capacity !== undefined ? Number(product.capacity) : null,
            color: product.color ?? '#6b7280',
        }));
        this.productMap = new Map(this.products.map((product) => [product.id, product]));
        const defaultDate = toDate(data.defaultDate ?? new Date(), new Date());
        const defaultStart = this.computeDefaultStart(defaultDate);
        const defaultEnd = addHours(defaultStart, 1);
        this.state = {
            view: VIEW_MONTH,
            date: defaultDate,
            filter: 'all',
            events: (data.events ?? []).map((event) => this.normalizeEvent(event)),
            showAddModal: false,
            addForm: {
                productId: this.products[0]?.id ?? '',
                start: formatDateTimeLocal(defaultStart),
                end: formatDateTimeLocal(defaultEnd),
                guestName: '',
                roomNumber: '',
            },
            showEditModal: false,
            editForm: null,
            error: { add: '', edit: '' },
        };
        this.render();
    }

    normalizeEvent(event) {
        const start = toDate(event.start, new Date());
        let endCandidate = toDate(event.end, addHours(start, 1));
        if (Number.isNaN(endCandidate.valueOf()) || endCandidate <= start) {
            endCandidate = addHours(start, 1);
        }
        const end = endCandidate;
        const productId = String(event.product_id ?? event.productId ?? '');
        const product = this.productMap.get(productId);
        const guestName = event.guest_name ?? event.guestName ?? '';
        const roomNumber = event.room_number ?? event.roomNumber ?? '';
        const isBlocked = Boolean(event.is_blocked ?? event.isBlocked ?? (guestName === '__block__'));
        const productName = event.product_name ?? event.productName ?? product?.name ?? 'Calendar Booking';

        return {
            id: event.id ?? `${productId}-${start.getTime()}`,
            productId,
            productName,
            guestName,
            roomNumber,
            isBlocked,
            start,
            end,
            title: event.title ?? this.buildTitle(productName, guestName, roomNumber, isBlocked),
        };
    }

    buildTitle(productName, guestName, roomNumber, isBlocked) {
        if (isBlocked) {
            return `${productName} - Unavailable`;
        }

        const guestLabel = guestName ? guestName : 'Guest';
        const roomLabel = roomNumber ? ` (Room ${roomNumber})` : '';

        return `${productName} - ${guestLabel}${roomLabel}`;
    }

    computeDefaultStart(date) {
        const base = toDate(date, new Date());
        base.setHours(9, 0, 0, 0);
        return base;
    }

    setState(updates) {
        this.state = {
            ...this.state,
            ...updates,
        };
        this.render();
    }

    updateAddForm(updates) {
        this.setState({
            addForm: {
                ...this.state.addForm,
                ...updates,
            },
        });
    }

    updateEditForm(updates) {
        if (!this.state.editForm) {
            return;
        }
        this.setState({
            editForm: {
                ...this.state.editForm,
                ...updates,
            },
        });
    }

    isCapacityAvailable(productId, start, end, excludeId = null) {
        const product = this.productMap.get(productId);
        const capacity = product?.capacity ?? null;

        if (!capacity || capacity <= 0) {
            return true;
        }

        const overlapping = this.state.events.filter((event) => {
            if (event.productId !== productId) {
                return false;
            }
            if (excludeId && event.id === excludeId) {
                return false;
            }
            return event.start < end && event.end > start;
        });

        return overlapping.length < capacity;
    }

    openAddModal(date) {
        const targetDate = toDate(date ?? this.state.date, this.state.date);
        const defaultStart = this.computeDefaultStart(targetDate);
        const defaultEnd = addHours(defaultStart, 1);
        this.setState({
            showAddModal: true,
            error: { ...this.state.error, add: '' },
            addForm: {
                productId: this.state.filter !== 'all' ? this.state.filter : this.products[0]?.id ?? '',
                start: formatDateTimeLocal(defaultStart),
                end: formatDateTimeLocal(defaultEnd),
                guestName: '',
                roomNumber: '',
            },
        });
    }

    openEditModal(eventId) {
        const event = this.state.events.find((item) => item.id === eventId);
        if (!event) {
            return;
        }

        this.setState({
            showEditModal: true,
            error: { ...this.state.error, edit: '' },
            editForm: {
                id: event.id,
                productId: event.productId,
                productName: event.productName,
                guestName: event.isBlocked ? '' : event.guestName,
                roomNumber: event.roomNumber ?? '',
                start: formatDateTimeLocal(event.start),
                end: formatDateTimeLocal(event.end),
                isBlocked: event.isBlocked,
            },
        });
    }

    closeModals() {
        this.setState({ showAddModal: false, showEditModal: false, error: { add: '', edit: '' } });
    }

    changeView(view) {
        if (![VIEW_MONTH, VIEW_WEEK, VIEW_DAY].includes(view)) {
            return;
        }
        this.setState({ view });
    }

    changeFilter(filter) {
        const updates = { filter };
        if (filter !== 'all' && this.products.some((product) => product.id === filter)) {
            updates.addForm = { ...this.state.addForm, productId: filter };
        }
        this.setState(updates);
    }

    navigate(step) {
        if (this.state.view === VIEW_MONTH) {
            this.setState({ date: addMonths(this.state.date, step) });
        } else if (this.state.view === VIEW_WEEK) {
            this.setState({ date: addWeeks(this.state.date, step) });
        } else {
            this.setState({ date: addDays(this.state.date, step) });
        }
    }

    addBooking() {
        const { productId, start, end, guestName, roomNumber } = this.state.addForm;
        const startDate = toDate(start);
        const endDate = toDate(end);

        if (!productId) {
            this.setState({ error: { ...this.state.error, add: 'Please select a product.' } });
            return;
        }

        if (Number.isNaN(startDate.valueOf()) || Number.isNaN(endDate.valueOf()) || startDate >= endDate) {
            this.setState({ error: { ...this.state.error, add: 'Please provide a valid start and end time.' } });
            return;
        }

        if (!this.isCapacityAvailable(productId, startDate, endDate)) {
            this.setState({ error: { ...this.state.error, add: 'Maximum slots reached for this time.' } });
            return;
        }

        const product = this.productMap.get(productId);
        const isBlocked = guestName?.trim() === '__block__';

        const newEvent = {
            id: `${Date.now()}-${Math.random().toString(36).slice(2)}`,
            productId,
            productName: product?.name ?? 'Calendar Booking',
            guestName: guestName?.trim() ?? '',
            roomNumber: roomNumber?.trim() ?? '',
            isBlocked,
            start: startDate,
            end: endDate,
        };
        newEvent.title = this.buildTitle(newEvent.productName, newEvent.guestName, newEvent.roomNumber, isBlocked);

        this.setState({
            events: [...this.state.events, newEvent],
            showAddModal: false,
            error: { ...this.state.error, add: '' },
        });
    }

    updateBooking() {
        if (!this.state.editForm) {
            return;
        }

        const { id, start, end, guestName, roomNumber } = this.state.editForm;
        const startDate = toDate(start);
        const endDate = toDate(end);

        if (Number.isNaN(startDate.valueOf()) || Number.isNaN(endDate.valueOf()) || startDate >= endDate) {
            this.setState({ error: { ...this.state.error, edit: 'Please provide a valid start and end time.' } });
            return;
        }

        const event = this.state.events.find((item) => item.id === id);
        if (!event) {
            this.setState({ showEditModal: false });
            return;
        }

        if (!this.isCapacityAvailable(event.productId, startDate, endDate, id)) {
            this.setState({ error: { ...this.state.error, edit: 'Maximum slots reached for this time.' } });
            return;
        }

        const newGuestRaw = guestName?.trim() ?? '';
        const newRoom = roomNumber?.trim() ?? '';
        const blocked = newGuestRaw === '__block__';
        const normalizedGuest = blocked ? '__block__' : newGuestRaw;
        const productName = event.productName;

        const updatedEvents = this.state.events.map((item) => {
            if (item.id !== id) {
                return item;
            }
            return {
                ...item,
                start: startDate,
                end: endDate,
                guestName: normalizedGuest,
                roomNumber: newRoom,
                isBlocked: blocked,
                title: this.buildTitle(productName, normalizedGuest, newRoom, blocked),
            };
        });

        this.setState({
            events: updatedEvents,
            showEditModal: false,
            error: { ...this.state.error, edit: '' },
        });
    }

    deleteBooking() {
        if (!this.state.editForm) {
            return;
        }

        const filtered = this.state.events.filter((event) => event.id !== this.state.editForm.id);
        this.setState({ events: filtered, showEditModal: false, error: { ...this.state.error, edit: '' } });
    }

    filteredEvents() {
        if (this.state.filter === 'all') {
            return this.state.events;
        }
        return this.state.events.filter((event) => event.productId === this.state.filter);
    }

    eventsForDay(day) {
        return this.filteredEvents()
            .filter((event) => {
                const startOfDay = new Date(day);
                startOfDay.setHours(0, 0, 0, 0);
                const endOfDay = new Date(day);
                endOfDay.setHours(23, 59, 59, 999);
                return event.start < endOfDay && event.end > startOfDay;
            })
            .sort(compareByStart);
    }

    renderCalendar() {
        if (this.state.view === VIEW_MONTH) {
            return this.renderMonthView();
        }
        if (this.state.view === VIEW_WEEK) {
            return this.renderWeekView();
        }
        return this.renderDayView();
    }

    renderMonthView() {
        const firstOfMonth = new Date(this.state.date.getFullYear(), this.state.date.getMonth(), 1);
        const monthStart = startOfWeek(firstOfMonth);
        const weeks = [];
        let current = new Date(monthStart);

        for (let week = 0; week < 6; week += 1) {
            const days = [];
            for (let dayIndex = 0; dayIndex < 7; dayIndex += 1) {
                days.push(new Date(current));
                current = addDays(current, 1);
            }
            weeks.push(days);
        }

        const today = new Date();
        today.setHours(0, 0, 0, 0);

        return `
            <div class="grid grid-cols-7 gap-2">
                ${weeks
                    .map(
                        (days) => `
                            ${days
                                .map((day) => {
                                    const isCurrentMonth = day.getMonth() === this.state.date.getMonth();
                                    const isToday = sameDay(day, today);
                                    const events = this.eventsForDay(day);

                                    return `
                                        <div class="flex h-32 flex-col rounded border ${
                                            isCurrentMonth ? 'bg-white' : 'bg-gray-50'
                                        } ${isToday ? 'ring-2 ring-[#FF5E57]' : ''}" data-action="open-add" data-date="${
                                        day.getTime()
                                    }">
                                            <div class="flex items-center justify-between border-b px-2 py-1 text-xs font-semibold">
                                                <span class="${isCurrentMonth ? 'text-gray-800' : 'text-gray-400'}">${day.getDate()}</span>
                                                <span class="text-[10px] text-gray-400">${escapeHtml(day.toLocaleDateString(undefined, {
                                                    weekday: 'short',
                                                }))}</span>
                                            </div>
                                            <div class="flex-1 overflow-y-auto p-1 text-xs">
                                                ${events
                                                    .map((event) => this.renderEventChip(event))
                                                    .join('') || '<div class="text-gray-400">No bookings</div>'}
                                            </div>
                                        </div>
                                    `;
                                })
                                .join('')}
                        `,
                    )
                    .join('')}
            </div>
        `;
    }

    renderWeekView() {
        const weekStart = startOfWeek(this.state.date);
        const days = Array.from({ length: 7 }, (_, index) => addDays(weekStart, index));

        return `
            <div class="grid grid-cols-7 gap-2">
                ${days
                    .map((day) => {
                        const events = this.eventsForDay(day);
                        return `
                            <div class="flex h-48 flex-col rounded border bg-white" data-action="open-add" data-date="${
                                day.getTime()
                            }">
                                <div class="flex items-center justify-between border-b px-2 py-1 text-xs font-semibold text-gray-800">
                                    <span>${escapeHtml(day.toLocaleDateString(undefined, { weekday: 'short' }))}</span>
                                    <span>${pad(day.getDate())}/${pad(day.getMonth() + 1)}</span>
                                </div>
                                <div class="flex-1 overflow-y-auto p-1 text-xs">
                                    ${events
                                        .map((event) => this.renderEventBlock(event))
                                        .join('') || '<div class="text-gray-400">No bookings</div>'}
                                </div>
                            </div>
                        `;
                    })
                    .join('')}
            </div>
        `;
    }

    renderDayView() {
        const dayStart = new Date(this.state.date);
        dayStart.setHours(0, 0, 0, 0);
        const events = this.eventsForDay(dayStart);

        return `
            <div class="rounded border bg-white">
                <div class="flex items-center justify-between border-b px-3 py-2">
                    <div class="text-sm font-semibold text-gray-800">${escapeHtml(dayLabel(dayStart))}</div>
                    <button class="rounded border px-2 py-1 text-xs font-semibold text-gray-600 hover:bg-gray-100" data-action="open-add" data-date="${
                        dayStart.getTime()
                    }">Add booking</button>
                </div>
                <div class="divide-y">
                    ${events
                        .map(
                            (event) => `
                                <div class="flex flex-col gap-1 px-3 py-2 text-sm" data-action="open-edit" data-event-id="${escapeHtml(event.id)}">
                                    ${this.renderEventBlock(event)}
                                </div>
                            `,
                        )
                        .join('') || '<div class="px-3 py-4 text-sm text-gray-400">No bookings for this day.</div>'}
                </div>
            </div>
        `;
    }

    renderEventChip(event) {
        const color = this.productMap.get(event.productId)?.color ?? '#6b7280';
        return `
            <button class="mb-1 w-full truncate rounded px-1 py-0.5 text-left" style="background-color: ${color}; color: white" data-action="open-edit" data-event-id="${escapeHtml(event.id)}">
                <div class="truncate text-[11px] font-semibold">${escapeHtml(event.productName)}</div>
                <div class="truncate text-[10px]">${escapeHtml(formatTimeRange(event.start, event.end))}</div>
            </button>
        `;
    }

    renderEventBlock(event) {
        const color = this.productMap.get(event.productId)?.color ?? '#6b7280';
        return `
            <button class="mb-2 w-full rounded border px-2 py-1 text-left text-xs" data-action="open-edit" data-event-id="${escapeHtml(event.id)}" style="border-color: ${color}">
                <div class="font-semibold" style="color: ${color}">${escapeHtml(event.productName)}</div>
                <div class="text-[11px] text-gray-600">${escapeHtml(formatTimeRange(event.start, event.end))}</div>
                <div class="text-[11px] text-gray-700">${escapeHtml(event.isBlocked ? 'Unavailable' : event.guestName || 'Guest')}${event.roomNumber ? escapeHtml(` (Room ${event.roomNumber})`) : ''}</div>
            </button>
        `;
    }

    renderFilters() {
        const viewOptions = [
            { value: VIEW_MONTH, label: 'Month' },
            { value: VIEW_WEEK, label: 'Week' },
            { value: VIEW_DAY, label: 'Day' },
        ];

        const productOptions = [{ value: 'all', label: 'All' }, ...this.products.map((product) => ({ value: product.id, label: product.name }))];

        return `
            <div class="mb-4 flex flex-wrap items-center gap-4">
                <div class="inline-flex divide-x overflow-hidden rounded-md border">
                    ${viewOptions
                        .map((option) => `
                            <button type="button" class="px-3 py-1 text-sm transition ${
                                this.state.view === option.value ? 'bg-[#FF5E57] text-white' : 'text-gray-700 hover:bg-gray-100'
                            }" data-action="change-view" data-view="${escapeHtml(option.value)}">
                                ${escapeHtml(option.label)}
                            </button>
                        `)
                        .join('')}
                </div>
                <div class="inline-flex divide-x overflow-hidden rounded-md border">
                    ${productOptions
                        .map((option) => `
                            <button type="button" class="px-3 py-1 text-sm transition ${
                                this.state.filter === option.value ? 'bg-[#FF5E57] text-white' : 'text-gray-700 hover:bg-gray-100'
                            }" data-action="change-filter" data-filter="${escapeHtml(option.value)}">
                                ${escapeHtml(option.label)}
                            </button>
                        `)
                        .join('')}
                </div>
                <div class="flex items-center gap-1">
                    <button class="rounded border p-1 hover:bg-gray-100" data-action="navigate" data-step="-1" aria-label="Previous">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                    </button>
                    <button class="rounded border p-1 hover:bg-gray-100" data-action="navigate" data-step="1" aria-label="Next">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </button>
                </div>
                <button class="ml-auto flex items-center gap-1 rounded bg-black px-3 py-1 text-sm font-semibold text-white" data-action="open-add" data-date="${
                    this.state.date.getTime()
                }">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Add Booking
                </button>
            </div>
        `;
    }

    renderCapacities() {
        if (!this.products.length) {
            return '';
        }
        return `
            <div class="mb-2 text-sm text-gray-700">
                ${this.products
                    .map((product) => {
                        if (product.capacity === null || Number.isNaN(product.capacity)) {
                            return `<div>${escapeHtml(product.name)}</div>`;
                        }
                        return `<div>${escapeHtml(product.name)} maximum concurrent slots: ${product.capacity}</div>`;
                    })
                    .join('')}
            </div>
        `;
    }

    renderAddModal() {
        if (!this.state.showAddModal) {
            return '';
        }

        return `
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800/40">
                <div class="w-80 rounded-lg bg-white p-6 shadow-md">
                    <h2 class="mb-2 font-bold">Add Booking</h2>
                    <div class="flex flex-col gap-2">
                        <label class="text-sm">
                            Product
                            <select class="mt-1 w-full rounded border px-2 py-1" data-action="add-input" data-field="productId">
                                ${this.products
                                    .map(
                                        (product) => `
                                            <option value="${escapeHtml(product.id)}" ${
                                                this.state.addForm.productId === product.id ? 'selected' : ''
                                            }>${escapeHtml(product.name)}</option>
                                        `,
                                    )
                                    .join('')}
                            </select>
                        </label>
                        <label class="text-sm">
                            Name
                            <input type="text" class="mt-1 w-full rounded border px-2 py-1" data-action="add-input" data-field="guestName" value="${escapeHtml(this.state.addForm.guestName)}" placeholder="Guest name or __block__" />
                        </label>
                        <label class="text-sm">
                            Room/ID
                            <input type="text" class="mt-1 w-full rounded border px-2 py-1" data-action="add-input" data-field="roomNumber" value="${escapeHtml(this.state.addForm.roomNumber)}" />
                        </label>
                        <label class="text-sm">
                            Start
                            <input type="datetime-local" class="mt-1 w-full rounded border px-2 py-1" data-action="add-input" data-field="start" value="${escapeHtml(this.state.addForm.start)}" />
                        </label>
                        <label class="text-sm">
                            End
                            <input type="datetime-local" class="mt-1 w-full rounded border px-2 py-1" data-action="add-input" data-field="end" value="${escapeHtml(this.state.addForm.end)}" />
                        </label>
                    </div>
                    ${
                        this.state.error.add
                            ? `<p class="mt-2 text-sm text-red-500">${escapeHtml(this.state.error.add)}</p>`
                            : ''
                    }
                    <div class="mt-4 flex justify-end gap-2">
                        <button class="rounded border px-3 py-1 text-sm font-semibold hover:bg-gray-100" data-action="close-modal">Cancel</button>
                        <button class="rounded bg-black px-3 py-1 text-sm font-semibold text-white hover:bg-gray-900" data-action="submit-add">Add</button>
                    </div>
                </div>
            </div>
        `;
    }

    renderEditModal() {
        if (!this.state.showEditModal || !this.state.editForm) {
            return '';
        }

        const product = this.productMap.get(this.state.editForm.productId);

        return `
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800/40">
                <div class="w-80 rounded-lg bg-white p-6 shadow-md">
                    <h2 class="mb-2 font-bold">${escapeHtml(product?.name ?? 'Booking')}</h2>
                    <div class="flex flex-col gap-2">
                        <label class="text-sm">
                            Name
                            <input type="text" class="mt-1 w-full rounded border px-2 py-1" data-action="edit-input" data-field="guestName" value="${escapeHtml(this.state.editForm.guestName)}" placeholder="Guest name or __block__" />
                        </label>
                        <label class="text-sm">
                            Room/ID
                            <input type="text" class="mt-1 w-full rounded border px-2 py-1" data-action="edit-input" data-field="roomNumber" value="${escapeHtml(this.state.editForm.roomNumber)}" />
                        </label>
                        <label class="text-sm">
                            Start
                            <input type="datetime-local" class="mt-1 w-full rounded border px-2 py-1" data-action="edit-input" data-field="start" value="${escapeHtml(this.state.editForm.start)}" />
                        </label>
                        <label class="text-sm">
                            End
                            <input type="datetime-local" class="mt-1 w-full rounded border px-2 py-1" data-action="edit-input" data-field="end" value="${escapeHtml(this.state.editForm.end)}" />
                        </label>
                    </div>
                    ${
                        this.state.error.edit
                            ? `<p class="mt-2 text-sm text-red-500">${escapeHtml(this.state.error.edit)}</p>`
                            : ''
                    }
                    <div class="mt-4 flex justify-end gap-2">
                        <button class="rounded bg-red-500 px-3 py-1 text-sm font-semibold text-white hover:bg-red-600" data-action="delete-booking">Delete</button>
                        <button class="rounded bg-black px-3 py-1 text-sm font-semibold text-white hover:bg-gray-900" data-action="submit-edit">Save</button>
                        <button class="rounded border px-3 py-1 text-sm font-semibold hover:bg-gray-100" data-action="close-modal">Close</button>
                    </div>
                </div>
            </div>
        `;
    }

    render() {
        const calendar = `
            <div class="layout-content-container flex w-full max-w-full flex-col rounded-lg bg-white p-6 shadow-md" style="max-width: calc(100vw - 18rem)">
                <h1 class="mb-6 text-3xl font-bold text-[#181110]">Calendar</h1>
                ${this.renderFilters()}
                ${this.renderCapacities()}
                <div class="overflow-hidden rounded border bg-gray-50 p-3">
                    ${this.renderCalendar()}
                </div>
            </div>
            ${this.renderAddModal()}
            ${this.renderEditModal()}
        `;

        this.root.innerHTML = calendar;
        this.attachListeners();
    }

    attachListeners() {
        this.root.querySelectorAll('[data-action="change-view"]').forEach((button) => {
            button.addEventListener('click', () => {
                const view = button.getAttribute('data-view');
                this.changeView(view);
            });
        });

        this.root.querySelectorAll('[data-action="change-filter"]').forEach((button) => {
            button.addEventListener('click', () => {
                const filter = button.getAttribute('data-filter');
                this.changeFilter(filter);
            });
        });

        this.root.querySelectorAll('[data-action="navigate"]').forEach((button) => {
            button.addEventListener('click', () => {
                const step = Number(button.getAttribute('data-step'));
                this.navigate(step);
            });
        });

        this.root.querySelectorAll('[data-action="open-add"]').forEach((element) => {
            element.addEventListener('click', (event) => {
                const dateValue = Number(event.currentTarget.getAttribute('data-date'));
                this.openAddModal(new Date(dateValue));
            });
        });

        this.root.querySelectorAll('[data-action="open-edit"]').forEach((element) => {
            element.addEventListener('click', (event) => {
                event.stopPropagation();
                const eventId = event.currentTarget.getAttribute('data-event-id');
                this.openEditModal(eventId);
            });
        });

        this.root.querySelectorAll('[data-action="add-input"]').forEach((input) => {
            input.addEventListener('input', (event) => {
                const field = input.getAttribute('data-field');
                const value = event.target.value;
                if (field === 'start') {
                    const startValue = toDate(value);
                    if (!Number.isNaN(startValue.valueOf())) {
                        const defaultEnd = addHours(startValue, 1);
                        this.updateAddForm({ start: formatDateTimeLocal(startValue), end: formatDateTimeLocal(defaultEnd) });
                    } else {
                        this.updateAddForm({ start: value });
                    }
                    return;
                }
                this.updateAddForm({ [field]: value });
            });
        });

        this.root.querySelectorAll('[data-action="edit-input"]').forEach((input) => {
            input.addEventListener('input', (event) => {
                const field = input.getAttribute('data-field');
                const value = event.target.value;
                if (field === 'guestName') {
                    this.updateEditForm({ guestName: value, isBlocked: value.trim() === '__block__' });
                    return;
                }
                this.updateEditForm({ [field]: value });
            });
        });

        const closeButtons = this.root.querySelectorAll('[data-action="close-modal"]');
        closeButtons.forEach((button) => {
            button.addEventListener('click', () => this.closeModals());
        });

        const submitAdd = this.root.querySelector('[data-action="submit-add"]');
        if (submitAdd) {
            submitAdd.addEventListener('click', () => this.addBooking());
        }

        const submitEdit = this.root.querySelector('[data-action="submit-edit"]');
        if (submitEdit) {
            submitEdit.addEventListener('click', () => this.updateBooking());
        }

        const deleteBooking = this.root.querySelector('[data-action="delete-booking"]');
        if (deleteBooking) {
            deleteBooking.addEventListener('click', () => this.deleteBooking());
        }
    }
}

function addHours(date, amount) {
    const result = new Date(date);
    result.setHours(result.getHours() + amount);
    return result;
}

function formatTimeRange(start, end) {
    const formatTime = (date) => {
        if (!(date instanceof Date) || Number.isNaN(date.valueOf())) {
            return '--:--';
        }
        return `${pad(date.getHours())}:${pad(date.getMinutes())}`;
    };
    return `${formatTime(start)} - ${formatTime(end)}`;
}

if (rootElement) {
    new CalendarPageApp(rootElement, initialData);
}
