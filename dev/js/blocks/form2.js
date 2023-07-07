const form2 = document.querySelector("#form-2");
const pristineten2 = new Pristine(form2);
const sms = document.querySelector('[name="sms"]');

form2.addEventListener('submit', async function (e) {
  e.preventDefault();
  let valid = pristineten2.validate();  
  if (valid) {
    const formData = new FormData();
    formData.append("sms", sms.value);
    disabledButton('button-send-sms', true);

    await fetch('https://jsonplaceholder.typicode.com/posts', {
        method: 'POST',
        body: formData
      })
      .then(d => d.json())
      .then(res => {
        renderInfo('form-2', 'info-success');
      })
      .catch((e) => {
        renderInfo('form-2', 'info-error');
      })
  }
});