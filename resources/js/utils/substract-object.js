// source: https://github.com/jonathankeebler/subtract-object/blob/master/lib/subtractobject.js

const substractObject = function (body, instance) {
  if (body == undefined) return

  else if (Array.isArray(instance)) {
    var new_array = []
    instance.forEach(function (element) {
      if (body.map(function (e) { return JSON.stringify(e) }).indexOf(JSON.stringify(element)) < 0) {
        new_array.push(element)
      }
    })

    return new_array
  }
  else if (typeof instance == "object" && !(instance instanceof Date)) {
    var new_instance = {}
    Object.keys(instance).forEach(function (key) {
      if (Object.hasOwnProperty.call(body, key)) {
        var new_value = substractObject(body[key], instance[key])

        if (new_value == undefined) {
          delete new_instance[key]
        }
        else {
          new_instance[key] = new_value
        }
      }
      else {
        new_instance[key] = instance[key]
      }
    })

    return new_instance
  }
  else if (body == instance) {
    return
  }
  else {
    return instance
  }
}

export function substract_object (object, from) {
  return substractObject(object, from)
}
