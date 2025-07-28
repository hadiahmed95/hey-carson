export default {
  mounted(el, binding) {
    const options = binding.value || {}
    const threshold = options.threshold || 100
    const callback = options.callback || (() => {})
    //const disabled = options.disabled || false;
    const handleScroll = () => {

      const scrollTop = el.scrollTop
      const clientHeight = el.clientHeight
      const scrollHeight = el.scrollHeight

      if (scrollHeight - scrollTop - clientHeight < threshold) {
        callback()
      }
    }

    el.addEventListener('scroll', handleScroll)
    el._onScroll = handleScroll
  },
  unmounted(el) {
    if (el._onScroll) {
      el.removeEventListener('scroll', el._onScroll)
      delete el._onScroll
    }
  }
}
