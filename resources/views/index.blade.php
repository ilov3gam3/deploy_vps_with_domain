<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css"/>
</head>
<body>
<div id="app">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-3">
                <form>
                    <div class="row">
                        <input v-model="message" class="form-control" type="text" placeholder="message">
                    </div>
                    <div class="row mt-1">
                        <button v-on:click="create($event)" class="btn btn-success btn-block">send</button>
                    </div>

                </form>
            </div>
            <div class="col-md-9">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">id</th>
                        <th scope="col">message</th>
                        <th scope="col">created at</th>
                        <th scope="col">updated at</th>
                        <th scope="col">action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-for="(value, key) in list">
                        <tr>
                            <th scope="row">@{{value.id}}</th>
                            <td>@{{value.message}}</td>
                            <td>@{{new Date(value.created_at).toLocaleString()}}</td>
                            <td>@{{new Date(value.updated_at).toLocaleString()}}</td>
                            <td>
                                <div class="row">
                                    <div class="col-md-6">
                                        <button class="btn btn-block btn-warning" v-on:click="update_mess(key)" data-toggle="modal" data-target="#updateModal">update</button>
                                    </div>
                                    <div class="col-md-6">
                                        <button class="btn btn-block btn-danger" v-on:click="del_mess(value.id)">delete</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <input v-model="update_message" class="form-control" type="text" placeholder="message">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" v-on:click="confirm_update($event)">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.7.10/vue.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.1.3/axios.min.js"
        integrity="sha512-0qU9M9jfqPw6FKkPafM3gy2CBAvUWnYVOfNPDYKVuRTel1PrciTj+a9P3loJB+j0QmN2Y0JYQmkBBS8W+mbezg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    var app = new Vue({
        el: "#app",
        data: {
            'message': '',
            'list': [],
            'update_message' : '',
            'update_id' : 0
        },
        created() {
            this.load_mess()
        },
        methods: {
            create(e) {
                e.preventDefault()
                if (this.message === ""){
                    toastr.warning("Vui lòng nhập message")
                } else {
                    var payload = {
                        'message': this.message
                    }
                    axios
                        .post('send_message', payload)
                        .then((res) => {
                            if (res.data.status) {
                                this.load_mess();
                                this.message = ""
                                toastr.success("Thêm message thành công")
                            } else {
                                toastr.error("Thêm message lỗi")
                            }
                        })
                }

            },
            load_mess() {
                axios
                    .get('get_mess')
                    .then((res) => {
                        this.list = res.data.data
                    })
            },
            del_mess(id){
                var payload = {
                    'id' : id
                }
                axios
                    .post('del_mess', payload)
                    .then((res) => {
                        if (res.data.status) {
                            this.load_mess();
                            toastr.success("Xoá message thành công")
                        } else {
                            toastr.error("Xoá message lỗi")
                        }
                    })
            },
            update_mess(index){
                this.update_message = this.list[index].message;
                this.update_id = this.list[index].id;

            },
            confirm_update(e){
                e.preventDefault()
                var payload = {
                    'id' : this.update_id,
                    'message' : this.update_message
                }
                axios
                    .post('update_mess', payload)
                    .then((res) =>{
                        if (res.data.status) {
                            this.load_mess();
                            toastr.success("Sửa message thành công")
                        } else {
                            toastr.error("Sửa message lỗi")
                        }
                    })
            }
        }
    })
</script>
</html>
