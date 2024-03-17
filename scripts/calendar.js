document.addEventListener('DOMContentLoaded', function() {
    const calendar = document.getElementById('calendar');
    const daysOfWeek = ['Po', 'Út', 'St', 'Čt', 'Pá'];

    fetch('availableDates.php')
        .then(response => response.json())
        .then(data => {
            const availableDates = data;

            const currentDate = new Date();

            const headerRow = document.createElement('div');
            headerRow.classList.add('calendar-row');
            daysOfWeek.forEach(day => {
                const dayHeader = document.createElement('div');
                dayHeader.classList.add('day', 'header');
                dayHeader.textContent = day;
                headerRow.appendChild(dayHeader);
            });
            calendar.appendChild(headerRow);

            const firstDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
            const daysInMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate();

            for (let i = 1; i <= daysInMonth; i++) {
                const currentDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), i);
                const dayElement = document.createElement('div');
                dayElement.classList.add('day');
                dayElement.textContent = i;

                if (currentDay.getDay() === 0 || currentDay.getDay() === 6) {
                    dayElement.classList.add('sunday');
                } else {
                    dayElement.classList.add('day');
                }

                if (availableDates.includes(currentDay.toISOString().split('T')[0])) {
                    dayElement.classList.add('available');
                } else {
                    dayElement.classList.add('unavailable');
                }

                if (currentDay.toDateString() === currentDate.toDateString()) {
                    dayElement.classList.add('today');
                }

                dayElement.addEventListener('click', function() {
                    const selectedDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), i);
                    if (availableDates.includes(selectedDate.toISOString().split('T')[0])) {
                        alert('Selected date: ' + selectedDate.toDateString());
                    } else {
                        alert('This date is not available for selection.');
                    }
                });

                calendar.appendChild(dayElement);
            }
        })
        .catch(error => {
            console.error('Error fetching available dates:', error);
        });
});
