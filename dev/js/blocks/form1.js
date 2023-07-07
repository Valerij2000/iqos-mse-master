const radioButtons = document.querySelectorAll('.fieldset__radio input');
const form1 = document.querySelector("#form-1");
const inputMse = document.querySelector('[name="mse"]');
const pristineten1 = new Pristine(form1);

const radios = {
  device: 'MSE_Duos_',
  service: 'SVC_SMSSuppression'
}

for (let radio of radioButtons) {
  radio.addEventListener('change', function () {
    if (radio.name === 'device') {
      radios.device = this.value;
    }
    if (radio.name === 'service') {
      radios.service = this.value;
    }
  })
}

function cleanForm(info) {
  pristineten1.reset();
  renderInfo('form-1', info);
  disabledButton('button-request', false);
  form1.reset();
}

function sendForm(formData, service, device) {
  fetch('https://fastdelivery.store/mse/ucrm/', {
    method: 'POST',
    body: formData
  }).then(response => {

    if (!response.ok) {
      ym(93906561,'reachGoal','BackendEvent', {
        'project_name': 'mse_lead',
        'action': 'request_sent_error'
      });
      cleanForm('info-error');
      throw new Error('Error occurred!')
    } 
    return response.json();
  })
  .then((data) => {
    
    if (data.statusMessage == 'Deduplication: such Lead have been already created less than 1440 minutes ago.') {
      ym(93906561,'reachGoal','BackendEvent', {
        'project_name': 'mse_lead',
        'action': 'request_sent_already_been'
      });
      cleanForm('info-existed');
    } else if (data.statusMessage == 'RequestForLeadCreate completed successfully') {
      ym(93906561,'reachGoal','BackendEvent', {
        'project_name': 'mse_lead',
        'action': 'btn_send_request',
        'device': device,
        'service': service,
      });  
      cleanForm('info-success');
    }
    console.log(data.statusMessage)
  })
  .catch((err) => {
    console.log(err)
  }) 

}

form1.addEventListener('submit', async function (e) {
  e.preventDefault();
  let valid = pristineten1.validate();
  if (valid) {
    const formData = new FormData();
    const dataRaw = {};
    const unicode = radios.device + radios.service;
    const msecode = selectValueMask + inputMse.value;
    disabledButton('button-request', true);
    pristineten1.fields.forEach(el => {
      let inputNameField = el.input.name;
      let inputFieldValue = el.input.value;
      dataRaw[inputNameField];
      dataRaw[inputNameField] = inputFieldValue;
    });
    formData.append("lead_source", unicode);
    formData.append("name", dataRaw.name);
    formData.append("phone", dataRaw.tel.replace(/\D/gm, ''));
    formData.append("mse", msecode);

    let device = '';
    switch (radios.device) {
      case "MSE_Duos_":
        device = 'IQOS 3 DUOS';
        break;
      case "MSE_lil_":
        device = 'lil SOLID 2.0';
        break;
     
    }
    let service = '';
    switch (radios.service) {
      case "SVC_SMSSuppression":
        service = 'Сервисное обслуживание';
        break;
      case "QCLUB_SMSSuppression":
        service = 'Q CLUB';
        break;
      case "TRIN":
        service = 'Трейд-ин';
        break;
      case "TRY":
        service = 'Аренда';
        break;
      case "BUY":
        service = 'Покупка';
        break;
     
    }

    sendForm(formData, service, device);
    //console.log(answer);
  }});