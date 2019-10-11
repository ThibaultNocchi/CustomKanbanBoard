class API {

    constructor(url) {
        this.URL = url;
    }

    parse_response(response) {
        return response.json()
            .then(json => {

                if (json.success) {
                    return this.parse_single_object(json.response, json.type)
                } else {
                    throw json.exception
                }

            })
    }

    parse_single_object(o, type) {
        if (type === 'Board') {
            let board = new Board()
            board.response = o
            return board
        }
    }

    login(code) {
        return fetch(`${this.URL}board/${code}`)
    }

    register(name) {
        let datas = new FormData()
        datas.append('name', name)
        return fetch(`${this.URL}board`, { method: 'POST', body: datas })
    }

}

class Board {

    constructor() {
    }

    set response(resp) {
        this.name = resp.name
        this.code = resp.code
    }

}

module.exports = { API, Board }