document.addEventListener('DOMContentLoaded', function() {
    const CALENDAR_SCROLL_STORAGE_KEY = 'calendarDatePickerScrollPosition';

    const storeScrollPosition = () => {
        try {
            sessionStorage.setItem(CALENDAR_SCROLL_STORAGE_KEY, String(window.scrollY));
        } catch (error) {
            // Ignore storage errors (e.g. private browsing, quota exceeded)
        }
    };

    const restoreScrollPosition = () => {
        let storedPosition = null;
        try {
            storedPosition = sessionStorage.getItem(CALENDAR_SCROLL_STORAGE_KEY);
        } catch (error) {
            storedPosition = null;
        }

        if (storedPosition === null) {
            return;
        }

        try {
            const parsed = Number.parseInt(storedPosition, 10);
            if (!Number.isNaN(parsed) && parsed >= 0) {
                window.requestAnimationFrame(() => {
                    window.scrollTo({ top: parsed, behavior: 'auto' });
                });
            }
        } finally {
            try {
                sessionStorage.removeItem(CALENDAR_SCROLL_STORAGE_KEY);
            } catch (error) {
                // Ignore removal errors
            }
        }
    };

    restoreScrollPosition();

    const calendarDateInput = document.getElementById('calendarDatePicker');
    const calendarDateForm = calendarDateInput ? calendarDateInput.form : null;
    let isSubmittingCalendarDate = false;

    const submitCalendarDateForm = () => {
        if (!calendarDateForm || isSubmittingCalendarDate) {
            return;
        }
        isSubmittingCalendarDate = true;
        storeScrollPosition();
        calendarDateForm.submit();
    };

    if (calendarDateInput) {
        calendarDateInput.addEventListener('change', submitCalendarDateForm);
    }

    document.querySelectorAll('[data-calendar-date-nav="true"]').forEach((element) => {
        element.addEventListener('click', () => {
            storeScrollPosition();
        });
    });

    const startDatePicker = flatpickr("#startDatePicker", {
        dateFormat: 'Y-m-d',
        altFormat: 'J M, Y',
        altInput: true,
        onChange: function(selectedDates) {
            if (selectedDates.length > 0) {
                const startDate = selectedDates[0];

                // Set minDate for end date
                endDatePicker.set('minDate', startDate);
            }
        }
    });

    const endDatePicker = flatpickr("#endDatePicker", {
        dateFormat: 'Y-m-d',
        altFormat: 'J M, Y',
        altInput: true,
        onChange: function(selectedDates) {
            if (selectedDates.length > 0) {
                startDatePicker.set('maxDate', selectedDates[0]); // Set maxDate for start date
            }
        }
    });

    const arrivalPicker = flatpickr("#arrival-date", {
        dateFormat: 'Y-m-d',
        altFormat: 'J M, Y',
        altInput: true,
        minDate: 'today',
        onChange: function(selectedDates) {
            if (selectedDates.length > 0) {
                const arrivalDate = selectedDates[0];

                // Set minDate for departure
                departurePicker.set('minDate', arrivalDate);

                // If departure date is not set, default to one day after arrival
                if (!departurePicker.selectedDates.length) {
                    const nextDay = new Date(arrivalDate);
                    nextDay.setDate(nextDay.getDate() + 1);
                    departurePicker.setDate(nextDay, true); // true triggers onChange
                }
            }
        }
    });

    const departurePicker = flatpickr("#departure-date", {
        dateFormat: 'Y-m-d',
        altFormat: 'J M, Y',
        altInput: true,
        onChange: function(selectedDates) {
            if (selectedDates.length > 0) {
                arrivalPicker.set('maxDate', selectedDates[0]); // Set maxDate for arrival
            }
        }
    });


    const calendarDatePicker = flatpickr("#calendarDatePicker", {
        dateFormat: 'Y-m-d',
        altFormat: 'J M, Y',
        altInput: true,
        onChange: function() {
            submitCalendarDateForm();
        }
    });



})
