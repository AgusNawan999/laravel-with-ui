export const baseUrl = (url = '') => `${window.location.origin}/${url}`

export function responseValidate(error, loginUrl = '/login') {
  if (!error.response) {
    return 'server not responding'
  }

  if (error.response.status == 401) {
    window.location.href = loginUrl //just redirect to login page
    return 'API Token Expire'
  }

  if (error.response.status == 403) {
    //not authorized
    return 'Access Denied'
  }

  if (error.response.status == 419) {
    //session expire
    window.location.href = loginUrl //just redirect to login page
    return 'Refresh page!' //CSRF Token Expoire
  }

  return error
}

const strippingSupportIdResponse = (html) => {
  let title = 'Request Rejected',
    tail = '[Go Back]',
    temp = document.createElement('div')

  temp.innerHTML = html
  let cleanText = temp.textContent || temp.innerText
  return {
    title,
    text: cleanText
      .replace(title, '')
      .replace(tail, '')
      .split('.')
      .map((text, idx, row) =>
        idx == row.length - 1 ? `<br><br><strong style="color: #0a6ebd">${text}</strong>` : `${text}. `,
      )
      .join(''),
  }
}

const strippingSupportIdNextResponse = (html, config = null) => {
  const supportIdWrapper = document.createElement('div')
  supportIdWrapper.innerHTML = html

  let supportId = supportIdWrapper.querySelector('#sp-id')
  let waCs = supportIdWrapper.querySelector('#wa_ccss') || supportIdWrapper.querySelector('#wa_ccltik')

  if (supportId && waCs) {
    // remove unused character
    supportId = supportId.textContent.trim()
    waCs = waCs.textContent.trim('').replace(/(\s|[\u2700-\u27BF]|[\uE000-\uF8FF]|\uD83C[\uDC00-\uDFFF]|\uD83D[\uDC00-\uDFFF]|[\u2011-\u26FF]|\uD83E[\uDD10-\uDDFF])/g, '')

    const textMessage = encodeURI(`Halo Admin,\r\nSaya terkena Support ID: ${supportId} saat mengakses URL: ${config.url} dengan method ${config.method }`)
    const waTemplate = `https://api.whatsapp.com/send?phone=62${waCs.substring(1)}&text=${textMessage}`
    const content = supportIdWrapper.querySelector('.content')
    const title = content.querySelector('h2').textContent.replace()
    const text = content.querySelector('p').textContent

    return {
      text: `${text}<br /><br /><span class="text-danger fw-700">Support ID: ${supportId}<span>`,
      waTemplate,
      supportId ,
      title: title.replace(/([\u2700-\u27BF]|[\uE000-\uF8FF]|\uD83C[\uDC00-\uDFFF]|\uD83D[\uDC00-\uDFFF]|[\u2011-\u26FF]|\uD83E[\uDD10-\uDDFF])/g, ''),
    }
  }

  return { title: null, text: null, waTemplate: null, supportId: null }
}

export function catchSupportId({ config, status, data }) {
  if (
    status == '200'
    && JSON.stringify(data).toLocaleLowerCase().includes('sp-id')
  ) {
    const { title, text, waTemplate, supportId } = strippingSupportIdNextResponse(data, config)

    window
      .swal
      .mixin({
        customClass: {
          confirmButton: 'btn btn-success mr-2',
          cancelButton: 'btn btn-default mr-2',
        },
        buttonsStyling: false
      })
      .fire({
        title,
        icon: 'error',
        html: text,
        showCancelButton: true,
        reverseButtons: true,
        confirmButtonText: '<i class="fad fa-comment"></i> Laporkan Support ID',
        cancelButtonText: 'Tutup',
        preConfirm: () => waTemplate
      })
      .then(({ isDismissed, isConfirmed, value }) => {
        if (isDismissed) return

        if (isConfirmed && value) {
          window.open(value, '_blank')
        }
      })

    const error = new Error(`Request Anda terblokir Support Id: ${supportId}`)
    error.code = 405

    throw error
  }

  if (
    status == '200'
    && JSON.stringify(data).toLowerCase().includes('the requested url was rejected')
  ) {
    const { title, text } = strippingSupportIdResponse(data)
    window.swal.fire(title, text, 'error')

    const error = new Error(text)
    error.code = 405

    throw error
  }
}

export function rgb2hex(rgb, defaultValue = '#000000') {
  if (!rgb) return defaultValue

  const hex = (x) => ('0' + parseInt(x).toString(16)).slice(-2)
  rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/)

  return '#' + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3])
}

export function mobileDetection() {
  const toMatch = [
    /Android/i,
    /webOS/i,
    /iPhone/i,
    /iPad/i,
    /iPod/i,
    /BlackBerry/i,
    /Windows Phone/i
  ]

  return toMatch.some((toMatchItem) => navigator.userAgent.match(toMatchItem))
}
