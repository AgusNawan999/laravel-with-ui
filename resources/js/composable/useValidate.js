import { reactive } from 'vue'
import { useForm } from 'vee-validate'

export function useValidate(schema = {}) {
  const defaultSchema = {
    label: {},
    rules: {},
    init: {}
  }
  const mergeSchema = Object.assign(defaultSchema, schema)

  const { meta, errors, defineInputBinds, handleSubmit } = useForm({
    validationSchema: mergeSchema,
    initialValues: mergeSchema.init
  })

  const temp = {}
  Object.keys(schema).forEach(key => temp[key] = defineInputBinds(key))
  const form = reactive(temp)

  return {
    form,
    meta,
    errors,
    handleSubmit,
  }
}
