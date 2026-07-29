$(document).ready(function () {
    const hospitalDashboardData = {
        hospital: {
            id: 25,
            name: 'RS Siloam Kebon Jeruk',
            city: 'Jakarta Barat',
            province: 'DKI Jakarta',
            type: 'Private Hospital',
            target: 'Key Account',
            lastUpdate: '28 Jul 2026, 14:35'
        },

        departments: [
            {
                id: '',
                name: 'Instrument'
            },
            {
                id: 1,
                name: 'ICU'
            },
            {
                id: 2,
                name: 'PICU'
            },
            {
                id: 3,
                name: 'NICU'
            },
            {
                id: 4,
                name: 'IBS Serbaguna'
            },
            {
                id: 5,
                name: 'Radiotherapy'
            },
            {
                id: 6,
                name: 'Urology'
            }
        ],

        dashboard: {
            all: {
                leadPromo: {
                    lead: 4,
                    promo: 3,
                    total: 7
                },
                prospect: {
                    total: 18,
                    subtitle: '+3 prospects this month',
                    estimatedValue: 4850000000,
                    nearestEta: '15 Aug 2026',
                    pipeline: [
                        {
                            name: 'Lead',
                            value: 4
                        },
                        {
                            name: 'Promo',
                            value: 3
                        },
                        {
                            name: 'Prospect',
                            value: 7
                        },
                        {
                            name: 'Funnel',
                            value: 3
                        },
                        {
                            name: 'Hot Prospect',
                            value: 1
                        }
                    ]
                },

                installbase: {
                    total: 56,
                    businessUnitCount: 7,
                    units: [
                        {
                            name: 'Electrosurgery',
                            value: 15
                        },
                        {
                            name: 'RT Onco',
                            value: 12
                        },
                        {
                            name: 'ICU Anesthesia',
                            value: 9
                        },
                        {
                            name: 'Sarana OK',
                            value: 7
                        },
                        {
                            name: 'Urology',
                            value: 4
                        },
                        {
                            name: 'Cardiovascular',
                            value: 5
                        },
                        {
                            name: 'MOT',
                            value: 4
                        }
                    ]
                },

                sdm: {
                    total: 36,
                    decisionMakers: 8
                },

                validation: {
                    validated: 9,
                    total: 12,
                    statuses: [
                        {
                            name: 'Validated Departments',
                            value: 9,
                            status: 'success'
                        },
                        {
                            name: 'Pending Validation',
                            value: 2,
                            status: 'warning'
                        },
                    ]
                },

                mappings: [
                    {
                        department: 'Radiology',
                        updatedAgo: '2 months ago',
                        updatedBy: 'Jojo Pattinama',
                        status: 'Current'
                    },
                    {
                        department: 'Cathlab',
                        updatedAgo: '8 months ago',
                        updatedBy: 'Sales User',
                        status: 'Needs Update'
                    }
                ],

                survey: {
                    available: true,
                    lastUpdate: '10 Jul 2026',
                    updatedBy: 'Area Manager',
                    expansion: 'Yes',
                    budget: 'Planning',
                    competitor: 'GE Healthcare'
                }
            },

            1: {
                prospect: {
                    total: 7,
                    subtitle: '2 prospects near ETA PO',
                    estimatedValue: 2150000000,
                    nearestEta: '15 Aug 2026',
                    pipeline: [
                        {
                            name: 'Lead',
                            value: 1
                        },
                        {
                            name: 'Promo',
                            value: 1
                        },
                        {
                            name: 'Prospect',
                            value: 3
                        },
                        {
                            name: 'Negotiation',
                            value: 1
                        },
                        {
                            name: 'PO Process',
                            value: 1
                        }
                    ]
                },

                installbase: {
                    total: 12,
                    businessUnitCount: 2,
                    units: [
                        {
                            name: 'Radiology',
                            value: 10
                        },
                        {
                            name: 'Medical Surgical',
                            value: 2
                        }
                    ]
                },

                sdm: {
                    total: 9,
                    decisionMakers: 3
                },

                validation: {
                    validated: 1,
                    total: 1,
                    statuses: [
                        {
                            name: 'Validated',
                            value: 1,
                            status: 'success'
                        },
                        {
                            name: 'Pending Validation',
                            value: 0,
                            status: 'warning'
                        },
                        {
                            name: 'Need Revision',
                            value: 0,
                            status: 'danger'
                        }
                    ]
                },

                mappings: [
                    {
                        department: 'Radiology',
                        updatedAgo: '2 months ago',
                        updatedBy: 'Jojo Pattinama',
                        status: 'Current'
                    }
                ],

                survey: {
                    available: true,
                    lastUpdate: '10 Jul 2026',
                    updatedBy: 'Area Manager',
                    expansion: 'Yes',
                    budget: 'Approved',
                    competitor: 'Siemens Healthineers'
                }
            },

            2: {
                prospect: {
                    total: 4,
                    subtitle: '1 prospect needs follow-up',
                    estimatedValue: 1250000000,
                    nearestEta: '30 Sep 2026',
                    pipeline: [
                        {
                            name: 'Lead',
                            value: 1
                        },
                        {
                            name: 'Promo',
                            value: 0
                        },
                        {
                            name: 'Prospect',
                            value: 2
                        },
                        {
                            name: 'Funnel',
                            value: 1
                        },
                        {
                            name: 'Hot Prospect',
                            value: 0
                        }
                    ]
                },

                installbase: {
                    total: 8,
                    businessUnitCount: 2,
                    units: [
                        {
                            name: 'Cardiology',
                            value: 6
                        },
                        {
                            name: 'Critical Care',
                            value: 2
                        }
                    ]
                },

                sdm: {
                    total: 7,
                    decisionMakers: 2
                },

                validation: {
                    validated: 0,
                    total: 1,
                    statuses: [
                        {
                            name: 'Validated',
                            value: 0,
                            status: 'success'
                        },
                        {
                            name: 'Pending Validation',
                            value: 1,
                            status: 'warning'
                        },
                        {
                            name: 'Need Revision',
                            value: 0,
                            status: 'danger'
                        }
                    ]
                },

                mappings: [
                    {
                        department: 'Cathlab',
                        updatedAgo: '8 months ago',
                        updatedBy: 'Sales User',
                        status: 'Needs Update'
                    }
                ],

                survey: {
                    available: false
                }
            }
        }
    };

    initializeDashboard();

    function initializeDashboard() {
        renderHospitalHeader();
        renderDepartmentOptions();
        renderDashboard('all');
        bindEvents();
    }

    function renderHospitalHeader() {
        const hospital = hospitalDashboardData.hospital;

        $('#hospitalName').text(hospital.name);

        $('#hospitalLocation').text(
            `${hospital.city}, ${hospital.province}`
        );

        $('#hospitalType').text(hospital.type);
        $('#hospitalTarget').text(hospital.target);
        $('#hospitalLastUpdate').text(hospital.lastUpdate);
    }

    function renderDepartmentOptions() {
        const departmentSelect = $('#departmentFilter');

        departmentSelect.empty();

        hospitalDashboardData.departments.forEach(function (department) {
            departmentSelect.append(
                $('<option>', {
                    value: department.id,
                    text: department.name
                })
            );
        });
    }

    function bindEvents() {
        $('#departmentFilterForm').on('submit', function (event) {
            event.preventDefault();

            const departmentId = $('#departmentFilter').val();
            const key = departmentId || 'all';

            simulateLoading(function () {
                renderDashboard(key);
            });
        });

        $(document).on('click', '[data-module]', function () {
            const moduleName = $(this).data('module');
            const departmentId = $('#departmentFilter').val();

            Swal.fire({
                icon: 'info',
                title: formatModuleName(moduleName),
                html: `
                    Later this will open the existing
                    <strong>${formatModuleName(moduleName)}</strong>
                    page.<br><br>
                    Hospital ID:
                    <strong>${hospitalDashboardData.hospital.id}</strong><br>
                    Department ID:
                    <strong>${departmentId || 'All'}</strong>
                `,
                confirmButtonText: 'Okay'
            });
        });
    }

    function renderDashboard(key) {
        const data =
            hospitalDashboardData.dashboard[key]
            || hospitalDashboardData.dashboard.all;

        renderKpiCards(data);
        renderProspectPipeline(data.prospect);
        renderInstallbase(data.installbase);
        renderValidation(data.validation);
        renderMappings(data.mappings);
        renderSurvey(data.survey);
    }

    function renderKpiCards(data) {
            $('#leadPromoCount').text(
                data.leadPromo.total
            );

            $('#leadPromoSubtitle').text(
                `${data.leadPromo.lead} leads and ${data.leadPromo.promo} promos`
            );

            $('#prospectCount').text(
                data.prospect.total
            );

            $('#prospectSubtitle').text(
                data.prospect.subtitle
            );

            $('#installbaseCount').text(
                `${data.installbase.total} Units`
            );

            $('#installbaseSubtitle').text(
                `${data.installbase.businessUnitCount} business units`
            );

            $('#sdmCount').text(
                data.sdm.total
            );

            $('#sdmSubtitle').text(
                `${data.sdm.decisionMakers} decision makers`
            );
        }

    function renderProspectPipeline(prospect) {
        const list = $('#prospectPipelineList');

        list.empty();

        const maximumValue = Math.max(
            ...prospect.pipeline.map(function (item) {
                return item.value;
            }),
            1
        );

        prospect.pipeline.forEach(function (item) {
            const percentage = Math.round(
                (item.value / maximumValue) * 100
            );

            list.append(`
                <li class="summary-list-item">
                    <span class="summary-name">
                        ${escapeHtml(item.name)}
                    </span>

                    <div class="summary-progress">
                        <div class="progress">
                            <div
                                class="progress-bar"
                                role="progressbar"
                                style="width:${percentage}%"
                                aria-valuenow="${percentage}"
                                aria-valuemin="0"
                                aria-valuemax="100">
                            </div>
                        </div>
                    </div>

                    <span class="summary-value">
                        ${item.value}
                    </span>
                </li>
            `);
        });

        $('#prospectValue').text(
            formatRupiah(prospect.estimatedValue)
        );

        $('#nearestEta').text(
            prospect.nearestEta || '-'
        );
    }

    function renderInstallbase(installbase) {
        const list = $('#installbaseList');

        list.empty();

        if (!installbase.units.length) {
            list.html(`
                <li class="dashboard-empty-state">
                    <i class="fas fa-box-open"></i>
                    No installbase data
                </li>
            `);

            return;
        }

        installbase.units.forEach(function (item) {
            list.append(`
                <li class="summary-list-item">
                    <span class="summary-name">
                        ${escapeHtml(item.name)}
                    </span>

                    <span class="summary-value">
                        ${item.value} Units
                    </span>
                </li>
            `);
        });
    }

    function renderValidation(validation) {
        const list = $('#validationList');

        list.empty();

        validation.statuses.forEach(function (item) {
            list.append(`
                <li class="summary-list-item">
                    <span class="summary-name">
                        ${escapeHtml(item.name)}
                    </span>

                    <span class="badge ${getValidationBadgeClass(item.status)}">
                        ${item.value}
                    </span>
                </li>
            `);
        });
    }

    function renderMappings(mappings) {
        const container = $('#mappingList');

        container.empty();

        if (!mappings || !mappings.length) {
            container.html(`
                <div class="dashboard-empty-state">
                    <i class="fas fa-project-diagram"></i>
                    No mapping result available
                </div>
            `);

            return;
        }

        mappings.slice(0, 2).forEach(function (mapping) {
            const statusClass =
                mapping.status === 'Current'
                    ? 'mapping-status-current'
                    : 'mapping-status-outdated';

            container.append(`
                <div class="mapping-item">
                    <div class="d-flex justify-content-between align-items-start">

                        <div>
                            <div class="mapping-title">
                                ${escapeHtml(mapping.department)}
                            </div>

                            <div class="mapping-meta">
                                Updated ${escapeHtml(mapping.updatedAgo)}
                            </div>

                            <div class="mapping-meta">
                                By ${escapeHtml(mapping.updatedBy)}
                            </div>
                        </div>

                        <span class="mapping-status ${statusClass}">
                            ${escapeHtml(mapping.status)}
                        </span>

                    </div>
                </div>
            `);
        });
    }

    function renderSurvey(survey) {
        const container = $('#marketSurveyContent');

        container.empty();

        if (!survey || !survey.available) {
            container.html(`
                <div class="dashboard-empty-state">
                    <i class="fas fa-poll"></i>

                    <div class="font-weight-bold text-gray-700">
                        No market survey available
                    </div>

                    <div class="small mt-1">
                        Create a survey for this department.
                    </div>

                    <button
                        type="button"
                        class="btn btn-sm btn-primary mt-3"
                        data-module="survey-create">
                        Create Survey
                    </button>
                </div>
            `);

            return;
        }

        container.html(`
            <div class="survey-status-box mb-3">

                <div class="d-flex justify-content-between align-items-center">
                    <span class="font-weight-bold text-gray-800">
                        Survey Available
                    </span>

                    <span class="badge badge-success">
                        Completed
                    </span>
                </div>

                <div class="small text-muted mt-2">
                    Updated ${escapeHtml(survey.lastUpdate)}
                    by ${escapeHtml(survey.updatedBy)}
                </div>

            </div>

            <ul class="list-unstyled mb-0 summary-list">

                <li class="summary-list-item">
                    <span class="summary-name">
                        Hospital Expansion
                    </span>

                    <span class="summary-value">
                        ${escapeHtml(survey.expansion)}
                    </span>
                </li>

                <li class="summary-list-item">
                    <span class="summary-name">
                        Budget Status
                    </span>

                    <span class="summary-value">
                        ${escapeHtml(survey.budget)}
                    </span>
                </li>

                <li class="summary-list-item">
                    <span class="summary-name">
                        Main Competitor
                    </span>

                    <span class="summary-value text-right">
                        ${escapeHtml(survey.competitor)}
                    </span>
                </li>

            </ul>
        `);
    }

    function simulateLoading(callback) {
        $('#dashboardContent').addClass('dashboard-loading');

        window.setTimeout(function () {
            callback();

            $('#dashboardContent').removeClass(
                'dashboard-loading'
            );
        }, 300);
    }

    function getValidationBadgeClass(status) {
        const classes = {
            success: 'badge-success',
            warning: 'badge-warning',
            danger: 'badge-danger'
        };

        return classes[status] || 'badge-secondary';
    }

    function formatModuleName(moduleName) {
        return String(moduleName)
            .replaceAll('-', ' ')
            .replace(/\b\w/g, function (letter) {
                return letter.toUpperCase();
            });
    }

    function formatRupiah(value) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
        }).format(value || 0);
    }

    function escapeHtml(value) {
        return $('<div>')
            .text(value ?? '')
            .html();
    }
});
