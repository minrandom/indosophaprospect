function populateSelectFromDatalist(selectId, data, keterangan,) {
  var selectElement = $("#" + selectId);
  selectElement.empty();

  // Add placeholder option
  var placeholderOption = new Option(keterangan, '', true, true);
  selectElement.append(placeholderOption);

  // Populate options from data
  data.forEach(function (source) {
    var option = new Option(source.name, source.id);
    selectElement.append(option);
  });

  // Initialize Select2
  selectElement.select2({
    placeholder: keterangan,
    width: '100%' // Adjust the width to fit the container
  });
}


function populateProvinceFromDatalist(selectId, data, keterangan,) {
  var selectElement = $("#" + selectId);
  selectElement.empty();

  // Add placeholder option
  var placeholderOption = new Option(keterangan, '', true, true);
  selectElement.append(placeholderOption);

  // Populate options from data
  data.forEach(function (source) {
    var option = new Option(source.name, source.prov_region_code);
    selectElement.append(option);
  });

  // Initialize Select2
  selectElement.select2({
    placeholder: keterangan,
    width: '100%' // Adjust the width to fit the container
  });
}






function filter(){
  var etapo=[
    {id:1,name:"1 Bulan Ke depan"},
    {id:3,name:"3 Bulan Ke depan"},
    {id:6,name:"6 Bulan Ke depan"},
  ];

  var sasaran=[
    {id:1,name:"Key Account / Prioritas"},

  ];

  var temper=[

    {id:1,name:"LEAD"},
    {id:2,name:"PROSPECT"},
    {id:3,name:"FUNNEL"},
    {id:4,name:"HOT PROSPECT"},
    {id:5,name:"SUCCESS"},
    {id:-1,name:"MISSED"}
  ]

  return{
    etafilter:etapo,
    temper :temper,
    sasaran : sasaran
  };

}

function dropsuccess(){
  var dropreason =[
    {id:1,name:'Double Input'},
    {id:2,name:'Losing Tender'},
    {id:3,name:"Price and Budget"},
    {id:4,name:"TKDN"},
    {id:5,name:"Competitor or Any External Issue"}
  ];

  var successreason=[
    {id:1,name:'Price and Budget'},
    {id:2,name:'Quality'},
    {id:3,name:"Doctor Choice"},
    {id:4,name:"Management Trust"},
    {id:5,name:"Key Person"},
    {id:6,name:"Good Data"}];

    return {
      dropreason:dropreason,
      successreason:successreason
    };
}




function datarem() {
  var tiperem = [
    { id: 1, name: "Request Update" },
    { id: 2, name: "Check Double" },
    { id: 3, name: "Wrong Input" },
    { id: 4, name: "Find More Data" },
  ];
  var column = [
    { id: 0, name: "Kolom Apa Saja" },
    { id: 1, name: "Tanggal First Offer" },
    { id: 2, name: "Tanggal Demo" },
    { id: 3, name: "Tanggal Presentasi" },
    { id: 4, name: "Tanggal Penawaran Terakhir" },
    { id: 5, name: "User Status" },
    { id: 6, name: "Direksi Status" },
    { id: 7, name: "Purchasing Status" },
    { id: 8, name: "Anggaran Status" },
    { id: 9, name: "Jenis Anggaran" },
    { id: 10, name: "Eta PO Date" },
    { id: 11, name: "Chance" },
  ];
  return {
    tiperem: tiperem,
    column: column
  };

}



function editHosPopulateSelect(selectElement, data, selectedValue, select2Options) {
  selectElement.empty();
  selectElement.select2(select2Options);
  $.each(data, function (index, item) {
    if (selectedValue == item.name) {
      selectElement.append('<option value="' + item.name + '" selected>' + item.name + '</option>');
    } else {
      selectElement.append('<option value="' + item.name + '">' + item.name + '</option>');
    }
  });
}

function CatPopulateSelect(selectElement, data, selectedValue, select2Options) {
  selectElement.empty();
  selectElement.select2(select2Options);
  $.each(data, function (index, item) {
    if (selectedValue == item.id) {
      selectElement.append('<option value="' + item.id + '" selected>' + item.name + '</option>');
    } else {
      selectElement.append('<option value="' + item.id + '">' + item.name + '</option>');
    }
  });
}

function editConfPopulateSelect(selectElement, data, selectedValue, select2Options) {
  console.log(selectedValue);
  selectElement.empty();
  selectElement.select2(select2Options);
  $.each(data, function (index, item) {
    if (selectedValue == item.id) {
      selectElement.append('<option value="' + item.id + '" selected>' + item.name + '</option>');
    } else {
      selectElement.append('<option value="' + item.id + '">' + item.name + '</option>');
    }
  });
}



function configeditdata() {
  var jenis = [
    { id: 1, name: 'Unit' },
    { id: 2, name: 'Accessories' },
    { id: 3, name: 'Accessories/ Instrument' },
    { id: 4, name: 'Consumables' },
    { id: 5, name: 'Cst Package' },
    { id: 6, name: 'Option' },
    { id: 7, name: 'Option/Accessories' },
    { id: 8, name: 'Package' },
    { id: 9, name: 'Set' },
    { id: 10, name: 'Spareparts' },
    { id: 11, name: 'Service Package' },
];

  var uom = [
    { id:1, name: 'Unit' },
    { id:2, name: 'Shock' },
    { id:3, name: 'Package' },
    { id:4, name: 'Set' },
    { id:5, name: 'Pcs' },
    { id:6, name: 'Pack' },
    { id:7, name: 'Box' },
  ];
  return {
    jenis: jenis,
    uom: uom,


  };



}




function hoseditdata() {
  var HosCat = [
    { id: 1, name: "SMALL" },
    { id: 2, name: "MEDIUM" },
    { id: 3, name: "LARGE" },
    { id: 4, name: "MAJOR" },
  ];
  var tipe = [
    { id: 1, name: 'Swasta' },
    { id: 2, name: 'Swasta-Group' },
    { id: 3, name: 'Kementerian Kesehatan' },
    { id: 4, name: 'TNI / POLRI' },
    { id: 5, name: 'Yayasan / Organisasi' },
    { id: 6, name: 'Pemerintah' },
    { id: 7, name: 'Pendidikan' },
    { id: 8, name: 'Kementerian Lain' },
    { id: 9, name: 'Negeri' },
    { id:10, name:'Group-Hospital'}
  ];
  var kelas = [
    { id: 1, name: 'A' },
    { id: 2, name: 'B' },
    { id: 3, name: 'C' },
    { id: 4, name: 'D' },
    { id: 4, name: 'Belum ditetapkan' },

  ];

  var akreditas = [
    { id: 1, name: 'Belum Ditetapkan' },
    { id: 2, name: 'Tingkat Paripurna' },
    { id: 3, name: 'Tingkat Utama' },
    { id: 4, name: 'Lulus Perdana' },
    { id: 5, name: 'Tingkat Madya' },
    { id: 6, name: 'Tingkat Dasar' },
  ];
  var sasaran = [
    { id: 1, name: 'Need Review' },
    { id: 2, name: 'Potensial' },
    { id: 3, name: 'Key Account' },
    { id: 4, name: 'Prioritas' },
  ]
  return {
    HosCat: HosCat,
    tipe: tipe,
    kelas: kelas,
    akreditas: akreditas,
    sasaran: sasaran,

  };

};


function populateConsProductSelect(businessUnitId, categoryId, selectElementId) {
  var productDataElement = document.getElementById('productData');
  var getProductsUrl = productDataElement.getAttribute('data-url');
  $.ajax({
      url: getProductsUrl,
      type: 'GET',
      data: {
        businessUnitId: businessUnitId,
        categoryId: categoryId
      },
      success: function(response) {
          var productSelect = $(selectElementId);
          productSelect.empty();
          productSelect.append('<option value="">- Pilih Produk -</option>');

          response.products.forEach(function(product) {
              var option = $("<option>").val(product.id).text(product.name);
              productSelect.append(option);
          });


          productSelect.select2();
      }
  });
}

function populateAllConsProductSelects(businessUnitId, categoryId) {
  $('.cr8product').each(function() {
      populateConsProductSelect(businessUnitId, categoryId, this);
  });
}

function populateProductSelect(businessUnitId, categoryId, formId) {
  var productDataElement = document.getElementById('productData');
  var getProductsUrl = productDataElement.getAttribute('data-url');

  $.ajax({
    url: getProductsUrl, // Replace with your endpoint to fetch products based on business unit and category
    method: "GET",
    data: {
      businessUnitId: businessUnitId,
      categoryId: categoryId
    },
    success: function (response) {
      populateSelectFromDatalist(formId, response.products, "Pilih Produk");
      // Add an empty option as a placeholder

    }
  });
}

function flash(type, message) {
  $(".notify").html(`<div class="alert alert-` + type + ` alert-dismissible fade show" role="alert">
                          `+ message + `
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>`)
}



function validateQuantity() {
  var quantityInput = document.getElementById('qtyinput');
  var quantityWarning = document.getElementById('quantityWarning');

  if (quantityInput.value < 1) {
    quantityWarning.style.display = 'block';
  } else {
    quantityWarning.style.display = 'none';
  }
}

var mx = 0;

$(".drag").on({
  mousemove: function (e) {
    var mx2 = e.pageX - this.offsetLeft;
    if (mx) this.scrollLeft = this.sx + mx - mx2;
  },
  mousedown: function (e) {
    this.sx = this.scrollLeft;
    mx = e.pageX - this.offsetLeft;
  }
});

$(document).on("mouseup", function () {
  mx = 0;
});



