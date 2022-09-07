let passwordInput = $('#password')

$('.needs-validation').on('submit', function (e) {
    if (!this.checkValidity()) {
        e.preventDefault()
        e.stopPropagation()
    }

    $(this).addClass('was-validated')
})

$('#show-pass').on('click', () => {
    if (passwordInput.attr('type') === 'password'){
        passwordInput.attr('type', 'text')
        $('#icon-show-pass').removeClass('bi-eye-fill').addClass('bi-eye-slash-fill')
        $('#text-show-pass').html('Esconder')
    } else{
        passwordInput.attr('type', 'password')
        $('#icon-show-pass').removeClass('bi-eye-slash-fill').addClass('bi-eye-fill')
        $('#text-show-pass').html('Mostrar')
    }
})
