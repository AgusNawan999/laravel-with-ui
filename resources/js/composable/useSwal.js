import swal from 'sweetalert2'

const confirmSwalMixin = {
  customClass: {
    confirmButton: 'btn btn-primary mr-2',
    cancelButton: 'btn btn-danger mr-2',
  },
  buttonsStyling: false
}

export function useAlert() {
  return swal.mixin(confirmSwalMixin)
}

export function useToast() {
  return swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.onmouseenter = swal.stopTimer
      toast.onmouseleave = swal.resumeTimer
    }
  })
}

export function useConfirmation(options, preConfirm = null) {
  const defaultOptions = {
    title: 'Ooops',
    icon: 'info',
    html: null,
    text: null,
    showCancelButton: true,
    confirmButtonText: '<i class="fad fa-check-circle"></i> Ya, lanjutkan',
    cancelButtonText: '<i class="fad fa-times-circle"></i> Tidak, batalkan',
    reverseButtons: true,
    showLoaderOnConfirm: true,
    allowOutsideClick: false,
  }
  const mergeOptions = Object.assign(defaultOptions, options)
  return swal.mixin(confirmSwalMixin).fire({...mergeOptions, preConfirm})
}
