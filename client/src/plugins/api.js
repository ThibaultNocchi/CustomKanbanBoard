class API {

    constructor(url) {
        this.URL = url;
    }

    parse_response(response) {
        return response.json()
            .then(json => {

                if (json.success) {
                    if (json.type === 'Collection') {
                        return this.parse_multi_object(json.response, json.inner_type)
                    } else {
                        return this.parse_single_object(json.response, json.type)
                    }
                } else {
                    throw json.exception
                }

            })
    }

    parse_multi_object(o, inner_type) {
        let results = []
        o.forEach(element => {
            results.push(this.parse_single_object(element, inner_type))
        });
        return results;
    }

    parse_single_object(o, type) {
        if (type === 'Board') {
            let board = new Board()
            board.response = o
            return board
        }
        if (type === 'User') {
            let user = new User()
            user.response = o
            return user
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

    get_users(board) {
        return fetch(`${this.URL}user`, { headers: { "board": board.code } });
    }

    register_user(board, name) {
        let datas = new FormData()
        datas.append('name', name)
        return fetch(`${this.URL}user`, { method: 'POST', body: datas, headers: { "board": board.code } })
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

class User {
    constructor() { }
    set response(resp) {
        this.name = resp.name
    }
}

module.exports = { API, Board, User }