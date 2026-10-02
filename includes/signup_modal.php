<div class="modal fade" id="signup-modal">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Signup with PGLife
                </h5>

                <button type="button"
                        class="close"
                        data-dismiss="modal">

                    <span>&times;</span>

                </button>

            </div>


            <div class="modal-body">

                <form method="post"
                      action="api/signup_submit.php">


                    <!-- Full Name -->

                    <div class="input-group mb-3">

                        <div class="input-group-prepend">

                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>

                        </div>

                        <input type="text"
                               name="full_name"
                               class="form-control"
                               placeholder="Full Name"
                               required>

                    </div>


                    <!-- Phone -->

                    <div class="input-group mb-3">

                        <div class="input-group-prepend">

                            <span class="input-group-text">
                                <i class="fas fa-phone"></i>
                            </span>

                        </div>

                        <input type="text"
                               name="phone"
                               class="form-control"
                               placeholder="Phone Number"
                               required>

                    </div>


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


                    <!-- College Name -->

                    <div class="input-group mb-3">

                        <div class="input-group-prepend">

                            <span class="input-group-text">
                                <i class="fas fa-university"></i>
                            </span>

                        </div>

                        <input type="text"
                               name="college_name"
                               class="form-control"
                               placeholder="College Name"
                               required>

                    </div>


                    <!-- Gender -->

                    <div class="input-group mb-3">

                        <div class="input-group-prepend">

                            <span class="input-group-text">
                                <i class="fas fa-venus-mars"></i>
                            </span>

                        </div>

                        <select name="gender"
                                class="form-control"
                                required>

                            <option value="">
                                Select Gender
                            </option>

                            <option value="male">
                                Male
                            </option>

                            <option value="female">
                                Female
                            </option>

                        </select>

                    </div>


                    <!-- Create Account -->

                    <button type="submit"
                            class="btn btn-primary btn-block signup-button">

                        Create Account

                    </button>


                    <!-- Login -->

                    <div class="text-center mt-3">

                        Already have an account?

                        <a href="#"
                           data-dismiss="modal"
                           data-toggle="modal"
                           data-target="#login-modal">

                            Login

                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>