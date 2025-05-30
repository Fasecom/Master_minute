document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="search"]');
    const cards = document.querySelectorAll('.master-card');

    searchInput.addEventListener('input', function() {
        const searchValue = this.value.toLowerCase().trim();

        cards.forEach(card => {
            const masterName = card.querySelector('.h3-main').textContent.toLowerCase();
            if (masterName.includes(searchValue)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    });
}); 