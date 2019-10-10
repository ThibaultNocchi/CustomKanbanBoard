class API {

    constructor(url) {
        this.URL = url;
    }

    parse_response(response){
        return response.json()
            .then(function(json) {

                if(json.success) {

                    if(json.type === 'Board') {
                        let board = new Board()
                        board.response = json.response
                        return board
                    }

                }else{
                    throw json.exception
                }

            })
    }

    login(code) {
        return fetch(`${this.URL}board/${code}`)
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

module.exports = {API, Board}