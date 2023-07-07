const buttonCancelesSms = document.querySelector('#button-canceled-sms');
buttonCancelesSms.addEventListener('click', () => {
  renderScene('form-2', 'form-1');
})

const buttonComback = document.querySelectorAll('[data-info]');
buttonComback.forEach(button => {
  button.addEventListener('click', function() {
    renderScene(button.dataset.info, 'form-1');
  })
})