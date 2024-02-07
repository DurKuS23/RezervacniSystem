document.addEventListener('DOMContentLoaded', function() {
    const calendar = document.getElementById('calendar');
    const daysOfWeek = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

    // Get current date
    const currentDate = new Date();

    // Build header with days of the week
    const headerRow = document.createElement('div');
    headerRow.classList.add('calendar-row');
    daysOfWeek.forEach(day => {
        const dayHeader = document.createElement('div');
        dayHeader.classList.add('day', 'header');
        dayHeader.textContent = day;
        headerRow.appendChild(dayHeader);
    });
    calendar.appendChild(headerRow);

    // Get the first day of the current month
    const firstDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
    const daysInMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate();

    // Create calendar days
    for (let i = 1; i <= daysInMonth; i++) {
        const currentDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), i);
        const dayElement = document.createElement('div');
        dayElement.classList.add('day');
        dayElement.textContent = i;

        // Highlight weekends
        if (currentDay.getDay() === 0 || currentDay.getDay() === 6) {
            dayElement.classList.add('sunday');
        } else {
            dayElement.classList.add('day');
        }

        // Highlight today
        if (currentDay.toDateString() === currentDate.toDateString()) {
            dayElement.classList.add('today');
        }

        // Add click event to select a date
        dayElement.addEventListener('click', function() {
            const selectedDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), i);
            alert('Selected date: ' + selectedDate.toDateString());
            // You can customize this part to handle the selected date as needed
        });

        calendar.appendChild(dayElement);
    }
});
