// use: minHpLength:min,delimiter
const minHpLength = (value, [min = 10, delimiter = ' '], { field, label, name }) => {
  const cleanText = value?.split(delimiter)?.join('')
  const fallbackLabel = field || label || name

  if (cleanText?.length < min)
    return `${fallbackLabel} minimal ${min} karakter`

  return true
}

export { minHpLength }
