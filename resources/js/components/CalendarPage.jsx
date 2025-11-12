import React, { useEffect, useMemo, useState } from 'react';
import {
    Calendar as BigCalendar,
    Views,
    dateFnsLocalizer,
} from 'react-big-calendar';
import { addDays, addMonths, addWeeks, format, getDay, parse, startOfWeek } from 'date-fns';
import { enGB } from 'date-fns/locale';
import 'react-big-calendar/lib/css/react-big-calendar.css';

const IconChevronLeft = ({ className }) => (
    <svg
        className={className}
        viewBox="0 0 24 24"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
        stroke="currentColor"
        strokeWidth="2"
        strokeLinecap="round"
        strokeLinejoin="round"
    >
        <polyline points="15 18 9 12 15 6" />
    </svg>
);

const IconChevronRight = ({ className }) => (
    <svg
        className={className}
        viewBox="0 0 24 24"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
        stroke="currentColor"
        strokeWidth="2"
        strokeLinecap="round"
        strokeLinejoin="round"
    >
        <polyline points="9 18 15 12 9 6" />
    </svg>
);

const IconPlus = ({ className }) => (
    <svg
        className={className}
        viewBox="0 0 24 24"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
        stroke="currentColor"
        strokeWidth="2"
        strokeLinecap="round"
        strokeLinejoin="round"
    >
        <line x1="12" y1="5" x2="12" y2="19" />
        <line x1="5" y1="12" x2="19" y2="12" />
    </svg>
);

const locales = {
    'en-GB': enGB,
};

const localizer = dateFnsLocalizer({
    format,
    parse,
    startOfWeek,
    getDay,
    locales,
});

const FALLBACK_COLOR = '#6b7280';

const formatDateTimeLocal = (value) => format(value, "yyyy-MM-dd'T'HH:mm");

const buildTitle = (productName, guestName, roomNumber, isBlocked) => {
    if (isBlocked) {
        return `${productName} - Unavailable`;
    }

    const guestLabel = guestName ? guestName : 'Guest';
    const roomLabel = roomNumber ? ` (Room ${roomNumber})` : '';

    return `${productName} - ${guestLabel}${roomLabel}`;
};

const normalizeEvent = (event, productMap) => {
    const productId = String(event.product_id ?? event.productId ?? '');
    const product = productMap.get(productId);
    const productName = event.product_name ?? product?.name ?? 'Calendar Booking';
    const start = event.start instanceof Date ? event.start : new Date(event.start);
    const parsedEnd = event.end instanceof Date ? event.end : new Date(event.end);
    const end = parsedEnd > start ? parsedEnd : new Date(start.getTime() + 60 * 60 * 1000);
    const guestName = event.guest_name ?? event.name ?? '';
    const roomNumber = event.room_number ?? event.room ?? '';
    const isBlocked = Boolean(event.is_blocked) || guestName === '__block__';

    return {
        ...event,
        id: event.id ?? Date.now(),
        productId,
        productName,
        start,
        end,
        guestName,
        roomNumber,
        isBlocked,
        title: event.title ?? buildTitle(productName, guestName, roomNumber, isBlocked),
    };
};

const CalendarPage = ({ events = [], products = [], defaultDate }) => {
    const productOptions = useMemo(() => {
        return products.map((product) => ({
            id: String(product.id ?? ''),
            name: product.name,
            capacity: product.capacity ?? null,
            color: product.color,
        }));
    }, [products]);

    const productMap = useMemo(() => {
        return new Map(productOptions.map((product) => [product.id, product]));
    }, [productOptions]);

    const initialEvents = useMemo(() => {
        return (events ?? []).map((event) => normalizeEvent(event, productMap));
    }, [events, productMap]);

    const [view, setView] = useState(Views.MONTH);
    const [date, setDate] = useState(() => {
        if (defaultDate) {
            const parsedDate = new Date(defaultDate);
            if (!Number.isNaN(parsedDate.valueOf())) {
                return parsedDate;
            }
        }
        return new Date();
    });
    const [calendarEvents, setCalendarEvents] = useState(initialEvents);
    const [filter, setFilter] = useState('all');
    const [selectedEvent, setSelectedEvent] = useState(null);
    const [editStart, setEditStart] = useState('');
    const [editEnd, setEditEnd] = useState('');
    const [editUser, setEditUser] = useState('');
    const [editRoom, setEditRoom] = useState('');
    const [showAddModal, setShowAddModal] = useState(false);
    const [newProduct, setNewProduct] = useState(() => productOptions[0]?.id ?? '');
    const [newStart, setNewStart] = useState('');
    const [newEnd, setNewEnd] = useState('');
    const [newUser, setNewUser] = useState('');
    const [newRoom, setNewRoom] = useState('');
    const [addError, setAddError] = useState('');
    const [editError, setEditError] = useState('');

    useEffect(() => {
        if (filter !== 'all') {
            setNewProduct(filter);
        }
    }, [filter]);

    useEffect(() => {
        setCalendarEvents(initialEvents);
    }, [initialEvents]);

    const productCapacity = useMemo(() => {
        const capacities = {};
        productOptions.forEach((product) => {
            if (product.capacity !== null && product.capacity !== undefined) {
                capacities[product.id] = Number(product.capacity);
            }
        });
        return capacities;
    }, [productOptions]);

    const productColors = useMemo(() => {
        const colors = {};
        productOptions.forEach((product) => {
            colors[product.id] = product.color ?? FALLBACK_COLOR;
        });
        return colors;
    }, [productOptions]);

    const filteredEvents = useMemo(() => {
        if (filter === 'all') {
            return calendarEvents;
        }
        return calendarEvents.filter((event) => event.productId === filter);
    }, [calendarEvents, filter]);

    const eventPropGetter = (event) => {
        const backgroundColor = productColors[event.productId] ?? FALLBACK_COLOR;
        return {
            style: {
                backgroundColor,
                borderRadius: '4px',
                border: 'none',
                color: 'white',
                overflow: 'hidden',
                whiteSpace: 'nowrap',
                textOverflow: 'ellipsis',
            },
        };
    };

    const formatEventForState = (event, overrides = {}) => {
        const merged = { ...event, ...overrides };
        return normalizeEvent(merged, productMap);
    };

    const isCapacityAvailable = (productId, start, end, excludeId = null) => {
        const capacity = productCapacity[productId];
        if (!capacity || capacity <= 0) {
            return true;
        }

        const overlapping = calendarEvents.filter((event) => {
            if (event.productId !== productId) {
                return false;
            }
            if (excludeId !== null && event.id === excludeId) {
                return false;
            }
            return event.start < end && event.end > start;
        });

        return overlapping.length < capacity;
    };

    const openAddModal = (startDate) => {
        const defaultStart = startDate ? new Date(startDate) : new Date();
        const defaultEnd = new Date(defaultStart.getTime() + 60 * 60 * 1000);

        setNewStart(formatDateTimeLocal(defaultStart));
        setNewEnd(formatDateTimeLocal(defaultEnd));
        setNewUser('');
        setNewRoom('');
        setAddError('');
        setShowAddModal(true);
    };

    const handleSelectEvent = (event) => {
        setSelectedEvent(event);
        setEditStart(formatDateTimeLocal(event.start));
        setEditEnd(formatDateTimeLocal(event.end));
        setEditUser(event.guestName === '__block__' ? '' : event.guestName ?? '');
        setEditRoom(event.roomNumber ?? '');
        setEditError('');
    };

    const handleSave = () => {
        if (!selectedEvent) {
            return;
        }

        const start = new Date(editStart);
        const end = new Date(editEnd);

        if (Number.isNaN(start.valueOf()) || Number.isNaN(end.valueOf()) || start >= end) {
            setEditError('Please provide a valid start and end time.');
            return;
        }

        const productId = selectedEvent.productId;
        if (!isCapacityAvailable(productId, start, end, selectedEvent.id)) {
            setEditError('Maximum slots reached for this time.');
            return;
        }

        const guestName = editUser?.trim() ? editUser.trim() : selectedEvent.guestName;
        const roomNumber = editRoom?.trim() ?? '';
        const isBlocked = guestName === '__block__' || selectedEvent.isBlocked;

        setCalendarEvents((prev) =>
            prev.map((event) =>
                event.id === selectedEvent.id
                    ? formatEventForState(event, {
                          start,
                          end,
                          guestName,
                          roomNumber,
                          isBlocked,
                          title: buildTitle(event.productName, guestName, roomNumber, isBlocked),
                      })
                    : event,
            ),
        );

        setSelectedEvent(null);
    };

    const handleDelete = () => {
        if (!selectedEvent) {
            return;
        }
        setCalendarEvents((prev) => prev.filter((event) => event.id !== selectedEvent.id));
        setSelectedEvent(null);
    };

    const handlePrev = () => {
        if (view === Views.MONTH) {
            setDate(addMonths(date, -1));
        } else if (view === Views.WEEK) {
            setDate(addWeeks(date, -1));
        } else {
            setDate(addDays(date, -1));
        }
    };

    const handleNext = () => {
        if (view === Views.MONTH) {
            setDate(addMonths(date, 1));
        } else if (view === Views.WEEK) {
            setDate(addWeeks(date, 1));
        } else {
            setDate(addDays(date, 1));
        }
    };

    const handleAddBooking = () => {
        const start = new Date(newStart);
        const end = new Date(newEnd);

        if (!newProduct) {
            setAddError('Please select a product.');
            return;
        }

        if (Number.isNaN(start.valueOf()) || Number.isNaN(end.valueOf()) || start >= end) {
            setAddError('Please provide a valid start and end time.');
            return;
        }

        if (!isCapacityAvailable(newProduct, start, end)) {
            setAddError('Maximum slots reached for this time.');
            return;
        }

        const product = productMap.get(newProduct);
        const guestName = newUser?.trim() ?? '';
        const roomNumber = newRoom?.trim() ?? '';
        const isBlocked = guestName === '__block__';

        const newEvent = formatEventForState(
            {
                id: Date.now(),
                productId: newProduct,
                product_name: product?.name ?? 'Calendar Booking',
                start,
                end,
                guestName,
                roomNumber,
                title: buildTitle(product?.name ?? 'Calendar Booking', guestName, roomNumber, isBlocked),
                is_blocked: isBlocked,
            },
            {
                productId: newProduct,
                productName: product?.name ?? 'Calendar Booking',
                start,
                end,
                guestName,
                roomNumber,
                isBlocked,
            },
        );

        setCalendarEvents((prev) => [...prev, newEvent]);
        setShowAddModal(false);
        setAddError('');
        setNewUser('');
        setNewRoom('');
    };

    const handleSlotSelect = (slotInfo) => {
        setNewProduct(filter !== 'all' ? filter : productOptions[0]?.id ?? '');
        openAddModal(slotInfo?.start ?? new Date());
    };

    const handleOpenAddModal = () => {
        setNewProduct(filter !== 'all' ? filter : productOptions[0]?.id ?? '');
        openAddModal(date);
    };

    const closeAddModal = () => {
        setShowAddModal(false);
        setAddError('');
    };

    const closeEditModal = () => {
        setSelectedEvent(null);
        setEditError('');
    };

    const scrollToTime = useMemo(() => {
        const scrollTarget = new Date();
        scrollTarget.setHours(8, 0, 0, 0);
        return scrollTarget;
    }, []);

    return (
        <div
            className="layout-content-container flex flex-col w-full flex-1 rounded-lg bg-white p-6 shadow-md"
            style={{ maxWidth: 'calc(100vw - 18rem)' }}
        >
            <h1 className="mb-6 text-3xl font-bold text-[#181110]">Calendar</h1>
            <div className="mb-4 flex flex-wrap items-center gap-4">
                <div className="inline-flex divide-x overflow-hidden rounded-md border">
                    {[{
                        value: Views.MONTH,
                        label: 'Month',
                    }, {
                        value: Views.WEEK,
                        label: 'Week',
                    }, {
                        value: Views.DAY,
                        label: 'Day',
                    }].map((option) => {
                        const isActive = view === option.value;
                        const baseClasses = 'px-3 py-1 text-sm transition';
                        const activeClasses = 'bg-[#FF5E57] text-white';
                        const inactiveClasses = 'text-gray-700 hover:bg-gray-100';

                        return (
                            <button
                                key={option.value}
                                type="button"
                                onClick={() => setView(option.value)}
                                className={`${baseClasses} ${isActive ? activeClasses : inactiveClasses}`}
                            >
                                {option.label}
                            </button>
                        );
                    })}
                </div>

                <div className="ml-4 inline-flex divide-x overflow-hidden rounded-md border">
                    {[
                        { value: 'all', label: 'All' },
                        ...productOptions.map((product) => ({ value: product.id, label: product.name })),
                    ].map((option) => {
                        const isActive = filter === option.value;
                        const baseClasses = 'px-3 py-1 text-sm transition';
                        const activeClasses = 'bg-[#FF5E57] text-white';
                        const inactiveClasses = 'text-gray-700 hover:bg-gray-100';

                        return (
                            <button
                                key={option.value}
                                type="button"
                                onClick={() => setFilter(option.value)}
                                className={`${baseClasses} ${isActive ? activeClasses : inactiveClasses}`}
                            >
                                {option.label}
                            </button>
                        );
                    })}
                </div>

                <div className="ml-4 flex items-center gap-1">
                    <button onClick={handlePrev} className="rounded border p-1 transition hover:bg-gray-100">
                        <IconChevronLeft className="h-4 w-4" />
                    </button>
                    <button onClick={handleNext} className="rounded border p-1 transition hover:bg-gray-100">
                        <IconChevronRight className="h-4 w-4" />
                    </button>
                </div>
                <button
                    onClick={handleOpenAddModal}
                    className="ml-auto inline-flex items-center gap-1 rounded bg-black px-3 py-1 text-sm font-medium text-white transition hover:bg-gray-900"
                    disabled={!productOptions.length}
                >
                    <IconPlus className="h-4 w-4" />
                    Add Booking
                </button>
            </div>

            {productOptions.length > 0 && (
                <div className="mb-2 text-sm text-gray-700">
                    {productOptions.map((product) => (
                        <div key={product.id} className="flex items-center gap-2">
                            <span
                                className="inline-block h-3 w-3 rounded-full"
                                style={{ backgroundColor: productColors[product.id] ?? FALLBACK_COLOR }}
                            />
                            <span>
                                {product.name}
                                {product.capacity ? ` maximum concurrent slots: ${product.capacity}` : ''}
                            </span>
                        </div>
                    ))}
                </div>
            )}

            <BigCalendar
                localizer={localizer}
                events={filteredEvents}
                startAccessor="start"
                endAccessor="end"
                view={view}
                onView={setView}
                date={date}
                onNavigate={setDate}
                views={[Views.MONTH, Views.WEEK, Views.DAY]}
                toolbar={false}
                popup
                selectable
                showAllEvents={false}
                scrollToTime={scrollToTime}
                eventPropGetter={eventPropGetter}
                onSelectEvent={handleSelectEvent}
                onSelectSlot={handleSlotSelect}
                style={{ height: '600px', width: '100%', maxHeight: '600px' }}
            />

            {selectedEvent && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-gray-800/40">
                    <div className="w-80 rounded-lg bg-white p-6 shadow-md">
                        <h2 className="mb-2 font-bold">{selectedEvent.title}</h2>
                        <div className="flex flex-col gap-2">
                            <label className="text-sm">
                                Name
                                <input
                                    type="text"
                                    value={editUser}
                                    onChange={(event) => setEditUser(event.target.value)}
                                    className="mt-1 w-full rounded border px-2 py-1"
                                    placeholder="Guest name or __block__"
                                />
                            </label>
                            <label className="text-sm">
                                Room/ID
                                <input
                                    type="text"
                                    value={editRoom}
                                    onChange={(event) => setEditRoom(event.target.value)}
                                    className="mt-1 w-full rounded border px-2 py-1"
                                />
                            </label>
                            <label className="text-sm">
                                Start
                                <input
                                    type="datetime-local"
                                    value={editStart}
                                    onChange={(event) => setEditStart(event.target.value)}
                                    className="mt-1 w-full rounded border px-2 py-1"
                                />
                            </label>
                            <label className="text-sm">
                                End
                                <input
                                    type="datetime-local"
                                    value={editEnd}
                                    onChange={(event) => setEditEnd(event.target.value)}
                                    className="mt-1 w-full rounded border px-2 py-1"
                                />
                            </label>
                        </div>
                        {editError && <p className="mt-2 text-sm text-red-500">{editError}</p>}
                        <div className="mt-4 flex justify-end gap-2">
                            <button
                                onClick={handleDelete}
                                className="rounded bg-red-500 px-3 py-1 text-sm font-semibold text-white hover:bg-red-600"
                            >
                                Delete
                            </button>
                            <button
                                onClick={handleSave}
                                className="rounded bg-black px-3 py-1 text-sm font-semibold text-white hover:bg-gray-900"
                            >
                                Save
                            </button>
                            <button
                                onClick={closeEditModal}
                                className="rounded border px-3 py-1 text-sm font-semibold hover:bg-gray-100"
                            >
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            )}

            {showAddModal && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-gray-800/40">
                    <div className="w-80 rounded-lg bg-white p-6 shadow-md">
                        <h2 className="mb-2 font-bold">Add Booking</h2>
                        <div className="flex flex-col gap-2">
                            <label className="text-sm">
                                Product
                                <select
                                    value={newProduct}
                                    onChange={(event) => setNewProduct(event.target.value)}
                                    className="mt-1 w-full rounded border px-2 py-1"
                                >
                                    {productOptions.map((product) => (
                                        <option key={product.id} value={product.id}>
                                            {product.name}
                                        </option>
                                    ))}
                                </select>
                            </label>
                            <label className="text-sm">
                                Name
                                <input
                                    type="text"
                                    value={newUser}
                                    onChange={(event) => setNewUser(event.target.value)}
                                    className="mt-1 w-full rounded border px-2 py-1"
                                    placeholder="Guest name or __block__"
                                />
                            </label>
                            <label className="text-sm">
                                Room/ID
                                <input
                                    type="text"
                                    value={newRoom}
                                    onChange={(event) => setNewRoom(event.target.value)}
                                    className="mt-1 w-full rounded border px-2 py-1"
                                />
                            </label>
                            <label className="text-sm">
                                Start
                                <input
                                    type="datetime-local"
                                    value={newStart}
                                    onChange={(event) => {
                                        const value = event.target.value;
                                        setNewStart(value);
                                        const startValue = new Date(value);
                                        if (!Number.isNaN(startValue.valueOf())) {
                                            const endValue = new Date(startValue.getTime() + 60 * 60 * 1000);
                                            setNewEnd(formatDateTimeLocal(endValue));
                                        }
                                    }}
                                    className="mt-1 w-full rounded border px-2 py-1"
                                />
                            </label>
                            <label className="text-sm">
                                End
                                <input
                                    type="datetime-local"
                                    value={newEnd}
                                    onChange={(event) => setNewEnd(event.target.value)}
                                    className="mt-1 w-full rounded border px-2 py-1"
                                />
                            </label>
                        </div>
                        {addError && <p className="mt-2 text-sm text-red-500">{addError}</p>}
                        <div className="mt-4 flex justify-end gap-2">
                            <button
                                onClick={handleAddBooking}
                                className="rounded bg-black px-3 py-1 text-sm font-semibold text-white hover:bg-gray-900"
                                disabled={!productOptions.length}
                            >
                                Add
                            </button>
                            <button
                                onClick={closeAddModal}
                                className="rounded border px-3 py-1 text-sm font-semibold hover:bg-gray-100"
                            >
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
};

export default CalendarPage;
