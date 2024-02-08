document.addEventListener('DOMContentLoaded', function() {
    const calendar = document.getElementById('calendar');
    const daysOfWeek = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

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

        if (currentDay.toDateString() === currentDate.toDateString()) {
            dayElement.classList.add('today');
        }

        dayElement.addEventListener('click', function() {
            const selectedDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), i);
            alert('Selected date: ' + selectedDate.toDateString());
        });

        calendar.appendChild(dayElement);
    }
});
