const load = $('#load'), loadError = $('#load-error'), divManageTable = $('#manage-table'), formEdit = $('#form-edit')
const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
let lastId

tooltipTriggerList.map((tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl))

$('.needs-validation').on('submit', function (e) {
    if (!this.checkValidity()) {
        e.preventDefault()
        e.stopPropagation()
    } else{

    }

    $(this).addClass('was-validated')
})

$('#show-pass').on('click', () => {
    const passwordInput = $('#password')

    if (passwordInput.attr('type') === 'password') {
        passwordInput.attr('type', 'text')
        $('#icon-show-pass').removeClass('bi-eye-fill').addClass('bi-eye-slash-fill')
        $('#text-show-pass').html('Esconder')
    } else {
        passwordInput.attr('type', 'password')
        $('#icon-show-pass').removeClass('bi-eye-slash-fill').addClass('bi-eye-fill')
        $('#text-show-pass').html('Mostrar')
    }
})

$('#edit-value').on('show.bs.modal', function (e) {
    const data = e.relatedTarget, url = data.getAttribute('data-action'),
        productId = data.getAttribute('data-product-id'), value = data.getAttribute('data-value')
    let name = $('#name-' + productId).html()

    name = (name.toUpperCase() === name) ? name : name.toLowerCase()

    $('#name-product').html(name)
    $('#value-product').val(value)
    formEdit.attr('action', url)
})

$('#edit-capacity').on('show.bs.modal', function (e) {
    const data = e.relatedTarget, tableId = data.getAttribute('data-table-id'),
        url = data.getAttribute('data-action'), capacity = data.getAttribute('data-capacity')

    $('#edit-table-id').html(tableId)
    $('#capacity-table').val(capacity)
    formEdit.attr('action', url)
})

$('#delete-table').on('show.bs.modal', function (e) {
    const data = e.relatedTarget, tableId =  data.getAttribute('data-table-id'),
        url =  data.getAttribute('data-href')

    $('#delete-table-id').html(tableId)
    $('#btn-confirm-delete').attr('href', url)
})

$('#manage-table-close').on('click',  () => divManageTable.addClass('d-none'))

$('#period-payment').on('change', () => {
    $('#form-select').trigger('submit')
})

$('#detail-table').on('show.bs.modal',  (e) => {
    getDetails('table', e.relatedTarget.getAttribute('data-table-id'))
})

$('#detail-order').on('show.bs.modal', (e) => {
    getDetails('order', e.relatedTarget.getAttribute('data-order-id'))
})

$('.btn-manage-table').on('click', function () {
    const tableId = $(this).data('table-id'), divManage = $('#manage'), btnManage = $('#btn-manage')

    load.removeClass('d-none')
    loadError.addClass('d-none')
    divManage.addClass('d-none')
    btnManage.addClass('d-none')

    $('#manage-table-id').html(tableId)

    divManageTable.removeClass('d-none')
    axios.get(apiDetails.replace('0', tableId))
        .then(function ({data}) {
            load.addClass('d-none')
            divManage.removeClass('d-none')
            btnManage.removeClass('d-none')

            const productsHTML = generateHTML(data, false, false)
            const btnVacantTable =  $('#btn-vacant-table')

            if (typeof data[0] === 'undefined') {
                $('#btn-close-bill').addClass('d-none')
                btnVacantTable.removeClass('d-none')
                btnVacantTable.attr('href', btnVacantTable.data('href') + '/' + tableId)
            }
            else {
                btnVacantTable.addClass('d-none')
                $('#btn-close-bill').removeClass('d-none').data('table-id', tableId)
            }

            $('#products').html(productsHTML)
            $('#total-products').html(data.total)
        })
        .catch(function (error) {
            load.addClass('d-none')
            divManage.addClass('d-none')
            loadError.removeClass('d-none')
            console.log(error)
        })
})

$('#close-bill').on('show.bs.modal',  () => {
    const tableId = $('#btn-close-bill').data('table-id')
    $('#table-id').html(tableId)
})

$('#form-close-bill').on('submit', function (e) {
    if (!this.checkValidity()) {
        e.preventDefault()
        e.stopPropagation()
        $(this).addClass('was-validated')
    } else{
        e.preventDefault()
        $(this).addClass('was-validated')
        console.log($('#quantity-people-table').val())
        setTimeout(() => $(this).removeClass('was-validated').trigger('reset'), 1000)
    }
})

$(window).on('scroll', () => {
    if ($(window).scrollTop() > 62) {
        $('body').css('padding-top', 62)
    } else {
        $('body').css('padding-top', $(window).scrollTop())
    }
})

$(window).on('hashchange',  () => hashChange())


function getDetails(typeData, itemId) {
    const divDetail = $('#detail')

    load.removeClass('d-none')
    loadError.addClass('d-none')
    divDetail.addClass('d-none')

    $('#detail-' + typeData + '-id').html(itemId)

    axios.get(apiDetails.replace('0', itemId))
        .then(function ({data}) {
            load.addClass('d-none')
            divDetail.removeClass('d-none')
            let productsHTML = (typeData === 'table') ? generateHTML(data, itemId) : generateHTML(data)
            $('#products').html(productsHTML)
            $('#total-products').html(data.total)
        })
        .catch(function (error) {
            load.addClass('d-none')
            divDetail.addClass('d-none')
            loadError.removeClass('d-none')
            console.log(error)
        })
}


function generateHTML(items, tableId = false, links = true) {
    let itemsHTML = ''

    if (typeof items[0] === 'undefined') itemsHTML = '<li class="list-group-item text-muted text-center">Não há produtos nessa mesa!</li>'
    else {
        for (let i = 0; typeof items[i] !== 'undefined'; i++) {
            const item = items[i]
            itemsHTML += `<li class="list-group-item d-flex justify-content-between lh-sm">
                        <div>
                            <h6 class="my-1">
                                <span class="badge bg-primary rounded-pill me-1">${item['quantity']}</span>`

            if (links) itemsHTML +=  `<a href="${productItem}#product-${item.id}" class="text-decoration-none">${item.name}</a>`
            else itemsHTML += item.name

            itemsHTML +=    `</h6>
                            <small class="text-muted">${item['unitaryValue']}</small>
                        </div>
                        <div>
                            <span class="text-muted me-2">${item['value']}</span>`

            if (tableId) {
                itemsHTML += `<a class="text-danger fw-bold text-decoration-none"
                                 href="${deleteItem}/${tableId}/${item.id}">
                                <i class="bi bi-x"></i>
                              </a>`
            }

            itemsHTML += `</div>
                        </li>`
        }
    }

    return itemsHTML
}

function hashChange() {
    let hash = window.location.hash

    if (lastId) {
        $(lastId).removeClass('table-primary')
    }

    if (hash.startsWith('#product-') || hash.startsWith('#table-')) {
        if (window.location.hash) {
            let id = window.location.hash
            $(id).addClass('table-primary')
            lastId = id
        }
    } else {
        lastId = undefined
    }
}

hashChange()
