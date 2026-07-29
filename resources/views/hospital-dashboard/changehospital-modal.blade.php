<div class="modal fade"
     id="changeHospitalModal"
     tabindex="-1"
     role="dialog"
     aria-labelledby="changeHospitalModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered"
         role="document">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"
                    id="changeHospitalModalLabel">
                    Change Hospital
                </h5>

                <button type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="row">

                    <div class="col-md-5 mb-3">
                        <label for="hospitalSearch">
                            Search Hospital
                        </label>

                        <input type="text"
                               id="hospitalSearch"
                               class="form-control"
                               placeholder="Type hospital name">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="provinceFilter">
                            Province
                        </label>

                        <select id="provinceFilter"
                                class="form-control">
                            <option value="">
                                All Provinces
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <button type="button"
                                id="btnSearchHospital"
                                class="btn btn-primary btn-block hospital-search-button">
                            <i class="fas fa-search mr-1"></i>
                            Search
                        </button>
                    </div>

                </div>

                <div id="hospitalSearchResult">
                    <div class="text-center text-muted py-4">
                        Search for a hospital to continue.
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
