function multiplenumber(e) {
  key = e.keyCode || e.which;

  teclado = String.fromCharCode(key).toLowerCase();

  numeros = '0123456789';

  especiales = '8-37-38-46-164-46';

  teclado_especial = false;

  for (var i in especiales) {
    if (key == especiales[i]) {
      teclado_especial = true;
      Swal.fire({
        icon: 'error',
        title: 'Error de digitacion',
        text: 'Debes ingresar solo numeros y deben ser en un rango requerido',
        confirmButtonText: 'Aceptar',
        customClass: {
          confirmButton: 'btn btn-primary'
        }
      });
      break;
    }
  }

  if (numeros.indexOf(teclado) == -1 && !teclado_especial) {
    Swal.fire({
      icon: 'error',
      title: 'Error de digitacion',
      text: 'Debes ingresar solo numeros y deben ser en un rango requerido',
      customClass: {
        confirmButton: 'btn btn-primary'
      }
    });
    return false;
  }
}

function maxlengthNumber(obj) {
  if (obj.value.length > obj.maxLength) {
    obj.value = obj.value.slice(0, obj.maxLength);
    Swal.fire({
      icon: 'error',
      title: 'Error de digitacion',
      text: 'Debes ingresar solo numeros y deben ser en un rango requerido',
      customClass: {
        confirmButton: 'btn btn-primary'
      }
    });
  }
}
