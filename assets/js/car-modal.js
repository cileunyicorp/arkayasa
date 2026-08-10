document.addEventListener("DOMContentLoaded", function () {
    const roller = document.getElementById("year-roller");
    const yearInput = document.getElementById("year");

    if (!roller || !yearInput) return;

    // Deteksi tahun saat roller digulir
    roller.addEventListener("scroll", function () {
        const items = roller.querySelectorAll(".lottery-item[data-value]");
        const rollerRect = roller.getBoundingClientRect();
        const rollerCenter = rollerRect.top + (rollerRect.height / 2);

        let closestYear = "";
        let minDistance = Infinity;

        items.forEach(function (item) {
            const itemRect = item.getBoundingClientRect();
            const itemCenter = itemRect.top + (itemRect.height / 2);
            const distance = Math.abs(rollerCenter - itemCenter);

            if (distance < minDistance) {
                minDistance = distance;
                closestYear = item.dataset.value;
            }
        });

        if (closestYear) {
            yearInput.value = closestYear;
        }
    });
});