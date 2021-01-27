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
                    this.$toast.open({
                        message: 'Import started!',
                        type: 'warning',
                        position: 'top-right',
                        duration: 300000
                        // all of other options may go here
                    });
                    axios.post('/auth/api/import',
                        formData, {
                            headers: {
                                'Content-Type': 'multipart/form-data',
                                'Authorization': 'Bearer ' + token
                            }
                        }
                    ).then(res => {
                        console.log('SUCCESS!!');
                        //console.log(res.data);
                        //this.importList = res.data;
                        this.$toast.clear();
                        this.$toast.open({
                            message: res.data.message,
                            type: 'info',
                            position: 'top-right'
                            // all of other options may go here
                        });
                    })
                        .catch(e => {
                            console.error(e);
                            this.$toast.open({
                                message: 'Oops, something went wrong!',
                                type: 'danger',
                                position: 'top-right'
                                // all of other options may go here
                            });
                        });
                }

            },
            handleFileUpload() {
                this.file = this.$refs.file.files[0];
                //console.log('>>>> 1st element in files array >>>> ', this.file);
            }
        }
    }
</script>