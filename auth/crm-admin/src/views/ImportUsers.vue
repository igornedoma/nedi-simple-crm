<template>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="row justify-content-center">
            <div class="col-8">
                <br>
                <h2>Users import</h2>
                <form>
                    <label for="file" class="form-label">You can import users from CSV file.</label>
                    <input class="form-control" type="file" id="file" ref="file" v-on:change="handleFileUpload" required>
                    <br>
                    <button type="submit" class="btn btn-primary" v-on:click="submitFile">Submit</button>
                </form>
                <br>
                <h2>Result Box</h2>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                        <tr>
                            <th>Success</th>
                            <th>Status</th>
                            <th>Message</th>
                        </tr>
                        </thead>
                        <tbody v-if="importList">
                        <tr v-for="item in importList" :class="getRowColor(item.status)">
                            <td>{{getResult(item.success)}}</td>
                            <td>{{item.status}}</td>
                            <td>{{item.message}}</td>

                        </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </main>
</template>
<script>

    import axios from "axios";

    export default {
        name: 'ImportUsers',
        components: {
            axios
        },
        data () {
            return {
                file: null,
                importList: null
            }
        },
        methods: {
            submitFile() {
                if (this.file != null) {
                    let formData = new FormData();
                    let token = localStorage.getItem('authTokenSimpleCrm');
                    formData.append('csv', this.file);
                    //console.log('>> formData >> ', formData);

                    axios.post('/auth/api/import',
                        formData, {
                            headers: {
                                'Content-Type': 'multipart/form-data',
                                'Authorization': 'Bearer ' + token
                            }
                        }
                    ).then(res => {
                        //console.log('SUCCESS!!');
                        //console.log(res.data);
                        this.importList = res.data;

                        this.$toast.open({
                            message: 'Users were imported. Check the result box.',
                            type: 'info',
                            position: 'top-right'
                            // all of other options may go here
                        });
                    })
                        .catch(e => {
                            console.error(e);
                        });
                }

            },
            handleFileUpload() {
                this.file = this.$refs.file.files[0];
                //console.log('>>>> 1st element in files array >>>> ', this.file);
            },
            getRowColor (status) {
                if (status == 201) {
                    return "table-success";
                }
                else {
                    return "table-danger";
                }
            },
            getResult (result) {
                if (result == 1) {
                    return "Yes!";
                }
                else {
                    return "No.";
                }
            }
        }
    }
</script>