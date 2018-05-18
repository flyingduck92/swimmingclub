function togglePassword(id) {
    var id = document.getElementById(id);
    if(id.type === 'password') {
        id.type = 'text';
    } else {
        id.type = 'password';
    } 
}