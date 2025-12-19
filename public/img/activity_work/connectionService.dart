class ConnectionService {
  //*****************Live URl ****************/

  static var base_url = "https://nulinz.com/cogosmart/api";
  // static var update_popup = "$base_url/update_popup.php";

  //*****************Local URl ****************/
  // static var base_url = "http://192.168.29.225:8000/api";

  // static var base_url = "http://192.168.29.65:8081/api";
  static var update_popup = "$base_url/update_popup.php";

  //Login Register
  static var register = "$base_url/auth/register";
  static var otp_validate = "$base_url/auth/register-otp";
  static var setup_account = "$base_url/sequence";
  static var setpswd = "$base_url/setpswd";
  static var check_user = "$base_url/check-user";
  static var logout = "$base_url/logout";
  static var send_otp = "$base_url/send-otp";
  static var validate_otp = "$base_url/validate-otp";
  static var emp_otp = "$base_url/auth/emp-otp";
  static var appVersion = "$base_url/version";
  static var changePassword = "$base_url/change-pswd";
  static var user_details = "$base_url/user-details";
  static var edit_user = "$base_url/edit-user";

  //Party Module
  static var createParty = "$base_url/create-party";
  static var PartyList = "$base_url/party-list";
  static var AddPartyCash = "$base_url/create-party-cash";
  static var PartyTransactionList = "$base_url/party-transac";
  static var partydetails = "$base_url/party-details";
  static var editpartydetails = "$base_url/party-edit";
  static var party_order = "$base_url/party-order";

  //Team Module
  static var create_team = "$base_url/create-team";
  static var team_list = "$base_url/team-list";
  static var team_addCash = "$base_url/team-addCash";
  static var team_returnCash = "$base_url/team-returnCash";
  static var team_history = "$base_url/team-transac";
  static var team_edit = "$base_url/team-edit";
  static var team_cashlist = "$base_url/team-cashlist";
  static var team_cash = "$base_url/retrive-teamcash";
  static var team_loadhis = "$base_url/team-loadhis";

  //Product
  static var create_product = "$base_url/create-product";
  static var update_product = "$base_url/update-product";
  static var product_list = "$base_url/product-list";
  static var product_delete = "$base_url/product-delete";
  static var activeproduct = "$base_url/active-product";
  //Farmer Module
  static var createFarmer = "$base_url/create-farmer";
  static var Farmerlist = "$base_url/farmer-list";
  static var AddFarmerCash = "$base_url/create-farmer-cash";
  static var FarmerTransactionList = "$base_url/farmer-transac";
  static var Farmerdetails = "$base_url/farmer-details";
  static var editFarmerdetails = "$base_url/farmer-edit";
  static var FarmerAdvance = "$base_url/farmer-advancelist";
  static var FarmerPurchase = "$base_url/farmer-purchase";

  //Load
  static var createLoad = "$base_url/create-load";
  static var LoadList = "$base_url/load-list";
  static var addload = "$base_url/add-load";
  static var addload_list = "$base_url/addload-list";
  static var shiftload_list = "$base_url/shiftload-list";
  static var assignload_list = "$base_url/assignload-list";
  static var completefetch = "$base_url/complete-fetch";
  static var assignCompleteList = "$base_url/assigncomp-list";
  static var completeLoadlist = "$base_url/comp-list";

  //Avalability
  static var createAvalability = "$base_url/create-avail";
  static var AvalCreatedList = "$base_url/avail-clist";
  static var AvalabilityList = "$base_url/avail-list";

// complete
  static var createInvoice = "$base_url/create-invoice";
  static var fetchInvoice = "$base_url/fetch-invoice";
  static var completeForm = "$base_url/comp-form";
  static var completeFetchData = "$base_url/comp-fetch-data";

  //permission
  static var permission = "$base_url/permission-list";
  static var dashboardlist = "$base_url/dashboard-values";

  static var notificationlist = "$base_url/notify-list";
}
