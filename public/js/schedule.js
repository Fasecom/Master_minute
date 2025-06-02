// JS для управления страницами графика смен, переключением и динамикой
window.Schedule = {
    currentPage: 0,
    totalPages: 1,
    setPage: function(page) {
        this.currentPage = page;
        document.dispatchEvent(new CustomEvent('schedule:page', { detail: { page } }));
    },
    nextPage: function() {
        if (this.currentPage < this.totalPages - 1) this.setPage(this.currentPage + 1);
    },
    prevPage: function() {
        if (this.currentPage > 0) this.setPage(this.currentPage - 1);
    },
    setTotalPages: function(total) {
        this.totalPages = total;
    }
};

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.schedule-nav-prev').forEach(btn => {
        btn.addEventListener('click', function() { window.Schedule.prevPage(); });
    });
    document.querySelectorAll('.schedule-nav-next').forEach(btn => {
        btn.addEventListener('click', function() { window.Schedule.nextPage(); });
    });
}); 