import colors from "@/plugins/colors.js";

class API {
  constructor(url) {
    this.URL = url;
  }

  parse_response(response) {
    return response.json().then(json => {
      if (json.success) {
        if (json.response) {
          if (json.type === "Collection") {
            return this.parse_multi_object(json.response, json.inner_type);
          } else {
            return this.parse_single_object(json.response, json.type);
          }
        }
      } else {
        throw json.exception;
      }
    });
  }

  parse_multi_object(o, inner_type) {
    let results = [];
    o.forEach(element => {
      results.push(this.parse_single_object(element, inner_type));
    });
    return results;
  }

  parse_single_object(o, type) {
    if (type === "Board") {
      let board = new Board();
      board.response = o;
      return board;
    }
    if (type === "User") {
      let user = new User();
      user.response = o;
      return user;
    }
    if (type === "Card") {
      let card = new Card();
      card.response = o;
      return card;
    }
  }

  login({ code }) {
    return fetch(`${this.URL}board/${code}`);
  }

  register({ name }) {
    let datas = new FormData();
    datas.append("name", name);
    return fetch(`${this.URL}board`, { method: "POST", body: datas });
  }

  get_users({ board }) {
    return fetch(`${this.URL}user`, { headers: { board: board.code } });
  }

  register_user({ board, name }) {
    let datas = new FormData();
    datas.append("name", name);
    return fetch(`${this.URL}user`, {
      method: "POST",
      body: datas,
      headers: { board: board.code }
    });
  }

  remove_user({ board, user }) {
    return fetch(`${this.URL}user/${user.id}`, {
      method: "DELETE",
      headers: { board: board.code }
    });
  }

  get_cards({ board }) {
    return fetch(`${this.URL}card`, { headers: { board: board.code } });
  }

  register_card({ board, name }) {
    let datas = new FormData();
    datas.append("name", name);
    return fetch(`${this.URL}card`, {
      method: "POST",
      body: datas,
      headers: { board: board.code }
    });
  }

  remove_card({ board, card }) {
    return fetch(`${this.URL}card/${card.id}`, {
      method: "DELETE",
      headers: { board: board.code }
    });
  }

  switch_cards({ board, card, newIndex }) {
    return fetch(`${this.URL}card/${card.id}/switch_to/${newIndex}`, {
      method: "PUT",
      headers: { board: board.code }
    });
  }

  register_task({ board, card, name }) {
    let datas = new FormData();
    datas.append("name", name);
    return fetch(`${this.URL}card/${card.id}`, {
      method: "POST",
      headers: { board: board.code },
      body: datas
    });
  }

  edit_name_card({ board, card, name }) {
    let datas = new FormData();
    datas.append("name", name);
    datas.append("_method", "PUT");
    return fetch(`${this.URL}card/${card.id}`, {
      method: "POST",
      headers: { board: board.code },
      body: datas
    });
  }

  edit_description_task({ board, task, description }) {
    let datas = new FormData();
    datas.append("description", description);
    datas.append("_method", "PUT");
    return fetch(`${this.URL}task/${task.id}`, {
      method: "POST",
      headers: { board: board.code },
      body: datas
    });
  }

  edit_name_task({ board, task, name }) {
    let datas = new FormData();
    datas.append("name", name);
    datas.append("_method", "PUT");
    return fetch(`${this.URL}task/${task.id}`, {
      method: "POST",
      headers: { board: board.code },
      body: datas
    });
  }

  edit_task({ board, task, color, users }) {
    let color_to_send = colors.colors_inverted[color];
    if (color_to_send !== undefined || users !== undefined) {
      let datas = new FormData();
      if (color_to_send !== undefined) datas.append("color", color_to_send);
      if (users !== undefined) {
        if (users.length === 0) datas.append("users[]", "");
        users.forEach(element => {
          datas.append("users[]", element);
        });
      }
      datas.append("_method", "PUT");
      return fetch(`${this.URL}task/${task.id}`, {
        method: "POST",
        headers: { board: board.code },
        body: datas
      });
    } else {
      return new Promise((resolve, reject) => {
        reject();
      });
    }
  }

  remove_task({ board, task }) {
    return fetch(`${this.URL}task/${task.id}`, {
      method: "DELETE",
      headers: { board: board.code }
    });
  }

  switch_tasks({ board, task, newIndex }) {
    let datas = new FormData();
    datas.append("order", newIndex);
    datas.append("_method", "PUT");
    return fetch(`${this.URL}task/${task.id}/switch_to`, {
      method: "POST",
      headers: { board: board.code },
      body: datas
    });
  }

  switch_tasks_into({ board, newCard, task, newIndex }) {
    let datas = new FormData();
    datas.append("order", newIndex);
    datas.append("card_to", newCard.id);
    datas.append("_method", "PUT");
    return fetch(`${this.URL}task/${task.id}/switch_into`, {
      method: "POST",
      headers: { board: board.code },
      body: datas
    });
  }
}

class Board {
  constructor() { }

  set response(resp) {
    this.name = resp.name;
    this.code = resp.code;
  }
}

class User {
  constructor() { }
  set response(resp) {
    this.id = resp.id;
    this.name = resp.name;
  }
}

class Card {
  constructor() { }
  set response(resp) {
    this.id = resp.id;
    this.name = resp.name;
    this.order = resp.order;
    this.tasks = [];
    resp.tasks.forEach(task_o => {
      let task = new Task();
      task.response = task_o;
      this.tasks.push(task);
    });
  }
}

class Task {
  constructor() { }
  get color() {
    return this._color;
  }
  set color(val) {
    this._color = colors.colors[val];
  }
  set response(resp) {
    this.id = resp.id;
    this.name = resp.name;
    this.description = resp.description;
    this.color = resp.color;
    this.users = resp.users;
  }
}

export default { API, Board, User, Card, Task, colors };
