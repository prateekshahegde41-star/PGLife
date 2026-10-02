<div class="modal fade" id="login-modal">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Login with PGLife
                </h5>

                <button type="button"
                        class="close"
                        data-dismiss="modal">

                    <span>&times;</span>

                </button>

            </div>


            <div class="modal-body">

                <form method="post"
                      action="api/login_submit.php">


                    <!-- Email -->

                    <div class="input-group mb-3">

                        <div class="input-group-prepend">

                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>

                        </div>

                        <input type="email"
                               name="email"
                               class="form-control"
                               placeholder="Email"
                               required>

                    </div>


                    <!-- Password -->

                    <div class="input-group mb-3">

                        <div class="input-group-prepend">

                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>

                        </div>

                        <input type="password"
                               name="password"
                               class="form-control"
                               placeholder="Password"
                               required>

                    </div>


                    <!-- Login button -->

                    <button type="submit"
                            class="btn btn-primary btn-block login-button">

                        Login

                    </button>


                    <!-- Signup -->

                    <div class="text-center mt-3">

                        Don't have an account?

                        <a href="#"
                           data-dismiss="modal"
                           data-toggle="modal"
                           data-target="#signup-modal">

                            Signup

                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>