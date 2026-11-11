
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".fp-schedule").forEach(schedule => {
        const dayFilter = schedule.querySelector(".fp-day-filter");
        const trainerFilter = schedule.querySelector(".fp-trainer-filter");
        const lessons = schedule.querySelectorAll(".fp-lesson");
        const empty = schedule.querySelector(".fp-empty");

        function applyFilters() {
            let visibleCount = 0;

            lessons.forEach(lesson => {
                const dayMatches =
                    dayFilter.value === "all" ||
                    lesson.dataset.day === dayFilter.value;

                const trainerMatches =
                    trainerFilter.value === "all" ||
                    lesson.dataset.trainer === trainerFilter.value;

                const show = dayMatches && trainerMatches;
                lesson.hidden = !show;

                if (show) visibleCount++;
            });

            empty.hidden = visibleCount !== 0;
        }

        dayFilter.addEventListener("change", applyFilters);
        trainerFilter.addEventListener("change", applyFilters);

        applyFilters();
    });
});
