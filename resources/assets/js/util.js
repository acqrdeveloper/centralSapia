export default {
  getStorage (name_storage) {
    return JSON.parse(window.localStorage.getItem(name_storage))
  },
}