document.addEventListener('DOMContentLoaded', function() {
    var clearSearchButton = document.getElementById('clear-search');
    clearSearchButton.addEventListener('click', function() {
        document.getElementById('filter-search').value = '';
        document.getElementById('adminForm').submit();
    });
    var addNewButton = document.getElementById('add-new-item');
    if (addNewButton) {
        addNewButton.addEventListener('click', function() {
            window.location.href = this.getAttribute('data-link');
        });
    }
});
