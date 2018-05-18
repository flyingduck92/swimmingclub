// IIFE
(function() {
    
    // ScaleHide Content
    var body = document.getElementById('body');
    var navToggle = document.getElementById('nav-toggle');
    var mainContent = document.getElementById('main-content');

    navToggle.addEventListener('click', function() {
        body.classList.toggle('menu-open');
        mainContent.classList.toggle('scale-content');
        return false;
    });

    // disable enter for submit form
    document.addEventListener('keypress', function(e){
        var code = e.keyCode || e.which;
        if(code == 13) {
            e.preventDefault();
            return false;
        }
    });

})();