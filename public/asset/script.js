// $('input[type=file]').change(function () {
//       document.getElementById('image_form_name')[0].innerHTML = document.getElementById('image_form_name').files[0].name;
//       console.log(document.getElementById('image_form_name').files[0].name);
// })
window.onload = function() {
  function sendForm(form) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', form.action);
    console.log(form);
    xhr.responseType = 'json';
    xhr.onload = () => {
      if (xhr.status !== 200) {
        document.querySelector('.result-base64').innerHTML = `<div><span>Ошибка</span></div>`;
        return;
      }
      const response = xhr.response;
      document.querySelector('.result-text').innerHTML = `<div><span>${response}</span></div>`;
      document.querySelector('.result-qr-file').innerHTML = `<div><img id="image" src="${response}" alt="" /></span></div>`;
    }
    let formData = new FormData(form);
    xhr.send(formData);
  }
  // при отправке формы
  // var formFile = document.getElementById('baseFile');
  // formFile.addEventListener('submit', (e) => {
  //   e.preventDefault();
  //   sendForm(formFile);
  // });
  // при отправке формы
  var formFileSecond = document.getElementById('qrText');
  formFileSecond.addEventListener('submit', (e) => {
    e.preventDefault();
    sendForm(formFileSecond);
  });
  // при отправке формы
  var formFileQr = document.getElementById('qrFile');
  formFileQr.addEventListener('submit', (e) => {
    e.preventDefault();
    sendForm(formFileQr);
  });
};