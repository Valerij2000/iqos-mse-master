function renderInfo(idForm, alertType) {
  document.querySelector(`#${idForm}`).classList.remove('active');
  document.querySelector(`#${alertType}`).classList.add('active');
}

function disabledButton(id, flag) {
  const button = document.querySelector(`#${id}`);
  if (flag) {
    button.classList.add('button-disabled');
    button.disabled = true;
  } else {
    button.classList.remove('button-disabled');
    button.disabled = false;
  }
}

function renderScene(hide, appear) {
  document.querySelector(`#${hide}`).classList.remove('active');
  document.querySelector(`#${appear}`).classList.add('active');
}