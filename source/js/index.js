$('.nav-packages a').on('click', e => {
    e.preventDefault()
    const item = e.target.getAttribute('href')
    $('.package').css('display', 'none')
    $('.nav-packages a').removeClass('selected')
    e.target.classList.add('selected')
    $(item).css('display', 'block')
})

$('.packages-select select').on('change', e => {
    const item = e.target.value
    $('.package').css('display', 'none')
    $('.nav-packages a').removeClass('selected')
    e.target.classList.add('selected')
    $(item).css('display', 'block')
})

$('.open-menu a').on('click', e => {
    e.preventDefault()
    $('.menu-mobile').toggle()
})