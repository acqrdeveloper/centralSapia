<template>
    <div class="card">
        <div class="card-body">
            <div class="form-inline">
                <div class="col-md-6">
                    <h3>User</h3>
                </div>
                <div class="col-md-6">
                    <div class="text-right">
                        <button class="btn btn-primary">Save</button>
                        <button class="btn btn-danger">Cancel</button>
                    </div>
                </div>

            </div>
            <hr>
            <form class="form-horizontal">
                <div class="form-group">
                    <div class="form-row">
                        <div class="col-md-6">
                            <label for="exampleInputName">First name</label>
                            <input class="form-control" id="exampleInputName" type="text" aria-describedby="nameHelp" placeholder="Enter first name">
                        </div>
                        <div class="col-md-6">
                            <label for="exampleInputLastName">Last name</label>
                            <input class="form-control" id="exampleInputLastName" type="text" aria-describedby="nameHelp" placeholder="Enter last name">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input class="form-control" id="exampleInputEmail1" type="email" aria-describedby="emailHelp" placeholder="Enter email">
                </div>
                <div class="form-group">
                    <div class="form-row">
                        <div class="col-md-6">
                            <label for="exampleInputPassword1">Password</label>
                            <input class="form-control" id="exampleInputPassword1" type="password" placeholder="Password">
                        </div>
                        <div class="col-md-6">
                            <label for="exampleConfirmPassword">Confirm password</label>
                            <input class="form-control" id="exampleConfirmPassword" type="password" placeholder="Confirm password">
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</template>

<script>
    export default {
        name: "user-profile"
    }
</script>

<style scoped>

</style>