// phone validate
const inputsPhone = document.querySelectorAll('[name="tel"]');

function inputValue() {
  if (this.value.substr(0, 1) === '8' || this.value.substr(0, 1) === '7') {
    this.value = this.value.replace(this.value.substr(0, 1), '+7 (');
  }
}
inputsPhone.forEach((e) => {
  e.addEventListener('input', inputValue);
  let mask = Maska.create(e, {
    mask: '+7 (###) ###-##-##'
  });
});
// name validate
Pristine.addValidator("full-fio", function (value) {
  // here `this` refers to the respective input element
  return value.trim().split(' ').length > 1;

}, "Введите корректное значение", 1, false);