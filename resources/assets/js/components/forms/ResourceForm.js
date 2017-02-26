export default class {
    constructor(action, urls) {
        this.action = action
        this.urls = urls
        this.processing = false
    }

    isCreating() {
        return this.action === 'creating'
    }

    isUpdating() {
        return this.action === 'updating'
    }

    isProcessing() {
        return this.processing === true
    }

    create(data, onSuccess, onError) {
        this.submit('post', this.urls.create, data, onSuccess, onError)
    }

    update(data, onSuccess, onError) {
        this.submit('put', this.urls.update, data, onSuccess, onError)
    }

    delete(data, onSuccess, onError) {
        this.submit('delete', this.ursl.delete, data, onSuccess, onError)
    }

    submit(method, url, data, onSuccess, onError) {
        if (this.processing) {
            return false
        }

        this.processing = true

        window.axios[method](url, data)
              .then(response => {
                this.processing = false

                if (typeof onSuccess === 'function') {
                    return onSuccess(response)
                }
              }, error => {
                this.processing = false

                if (typeof onError === 'function') {
                    return onError(error)
                }
              })
    }
}
