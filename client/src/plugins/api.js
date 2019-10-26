class API {

    constructor(url) {
        this.URL = url;
    }

    parse_response(response) {
        return response.json()
            .then(json => {

                if (json.success) {
                    if (json.response) {
                        if (json.type === 'Collection') {
                            return this.parse_multi_object(json.response, json.inner_type)
                        } else {
                            return this.parse_single_object(json.response, json.type)
                        }
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
        if (type === 'Card') {
            let card = new Card()
            card.response = o
            return card
        }
    }

    login({ code }) {
        return fetch(`${this.URL}board/${code}`)
    }

    register({ name }) {
        let datas = new FormData()
        datas.append('name', name)
        return fetch(`${this.URL}board`, { method: 'POST', body: datas })
    }

    get_users({ board }) {
        return fetch(`${this.URL}user`, { headers: { "board": board.code } });
    }

    register_user({ board, name }) {
        let datas = new FormData()
        datas.append('name', name)
        return fetch(`${this.URL}user`, { method: 'POST', body: datas, headers: { "board": board.code } })
    }

    remove_user({ board, user }) {
        return fetch(`${this.URL}user/${user.id}`, { method: 'DELETE', headers: { "board": board.code } })
    }

    get_cards({ board }) {
        return fetch(`${this.URL}card`, { headers: { "board": board.code } });
    }

    register_card({ board, name }) {
        let datas = new FormData()
        datas.append('name', name)
        return fetch(`${this.URL}card`, { method: 'POST', body: datas, headers: { "board": board.code } })
    }

    remove_card({ board, card }) {
        return fetch(`${this.URL}card/${card.id}`, { method: 'DELETE', headers: { "board": board.code } })
    }

    switch_cards({ board, card, newIndex }) {
        return fetch(`${this.URL}card/${card.id}/switch_to/${newIndex}`, { method: 'PUT', headers: { "board": board.code } })
    }

    register_task({ board, card, name }) {
        let datas = new FormData()
        datas.append('name', name)
        return fetch(`${this.URL}card/${card.id}`, { method: 'POST', headers: { "board": board.code }, body: datas })
    }

    edit_name_card({board, card, name}) {
        let datas = new FormData()
        datas.append('name', name)
        datas.append('_method', 'PUT')
        return fetch(`${this.URL}card/${card.id}`, { method: 'POST', headers: { "board": board.code }, body: datas })
    }

    edit_description_task({ board, task, description }) {
        let datas = new FormData()
        datas.append('description', description)
        datas.append('_method', 'PUT')
        return fetch(`${this.URL}task/${task.id}`, { method: 'POST', headers: { "board": board.code }, body: datas })
    }

    edit_name_task({ board, task, name }) {
        let datas = new FormData()
        datas.append('name', name)
        datas.append('_method', 'PUT')
        return fetch(`${this.URL}task/${task.id}`, { method: 'POST', headers: { "board": board.code }, body: datas })
    }

    edit_color_task({ board, task, color }) {
        let datas = new FormData()
        datas.append('color', color)
        datas.append('_method', 'PUT')
        return fetch(`${this.URL}task/${task.id}`, { method: 'POST', headers: { "board": board.code }, body: datas })
    }

    remove_task({ board, task }) {
        return fetch(`${this.URL}task/${task.id}`, { method: 'DELETE', headers: { 'board': board.code } })
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
        this.id = resp.id
        this.name = resp.name
    }
}

class Card {
    constructor() { }
    set response(resp) {
        this.id = resp.id
        this.name = resp.name
        this.order = resp.order
        this.tasks = []
        resp.tasks.forEach(task_o => {
            let task = new Task()
            task.response = task_o
            this.tasks.push(task)
        });
    }
}

class Task {
    constructor() { }
    set response(resp) {
        this.id = resp.id
        this.name = resp.name
        this.description = resp.description
        this.color = resp.color
    }
}

module.exports = { API, Board, User, Card, Task }