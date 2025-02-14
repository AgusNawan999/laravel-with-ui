export function configureCompat(Vue) {
  Vue.configureCompat({
    MODE: 3,
    // required by Vue-router
    CONFIG_OPTION_MERGE_STRATS: 'suppress-warning',
    GLOBAL_PRIVATE_UTIL: 'suppress-warning',
    GLOBAL_PROTOTYPE: 'suppress-warning',

    // required due to global mixin on vue-router
    INSTANCE_EVENT_HOOKS: 'suppress-warning',
    OPTIONS_DESTROYED: 'suppress-warning',
    INSTANCE_EVENT_EMITTER: 'suppress-warning',

    // required by portal-vue
    GLOBAL_SET: 'suppress-warning',

    // globals
    GLOBAL_EXTEND: 'suppress-warning',
    GLOBAL_MOUNT: 'suppress-warning',

    // functional does not work how you think ;)
    COMPONENT_FUNCTIONAL: 'suppress-warning',
    RENDER_FUNCTION: 'suppress-warning',

    CUSTOM_DIR: 'suppress-warning',

    INSTANCE_SCOPED_SLOTS: 'suppress-warning',
    INSTANCE_LISTENERS: 'suppress-warning',
    WATCH_ARRAY: 'suppress-warning',
    OPTIONS_BEFORE_DESTROY: 'suppress-warning',

    // vue-multiselect
    ATTR_ENUMERATED_COERCION: 'suppress-warning',

    // vee-validate
    ATTR_FALSE_VALUE: 'suppress-warning',
  })

  // See https://github.com/vuejs/vue-next/pull/4121 for details
  const originalVModelDynamicCreated = Vue.vModelDynamic.created
  const originalVModelDynamicBeforeUpdate = Vue.vModelDynamic.beforeUpdate

  Vue.vModelDynamic.created = function (el, binding, vnode) {
    originalVModelDynamicCreated.call(this, el, binding, vnode)
    if (!el._assign) {
      el._assign = () => { }
    }
  }

  Vue.vModelDynamic.beforeUpdate = function (el, binding, vnode) {
    originalVModelDynamicBeforeUpdate.call(this, el, binding, vnode)
    if (!el._assign) {
      el._assign = () => { }
    }
  }
}
