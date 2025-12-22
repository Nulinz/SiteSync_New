Sitesync_new: 

1) Under select projects->General->should list out all the employees
2) If no project has been assigned, select projects dropdown is not working.

Prompt:
working well in web: If i select general under select projects, also if i select any other project, In assign to: Showing the assigned employees. But in mobile: If i select general , In assign to: not showing any employees, also if i select any other project, In assign to: Showing the assigned employees. sort it and give me without bugs.


import 'dart:convert';
import 'dart:io';
import 'dart:typed_data';
import 'package:file_picker/file_picker.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:get/get.dart';
import 'package:sitesync/Components/Formfield.dart';
import 'package:sitesync/Theme/Colors.dart';
import 'package:sitesync/Theme/appTheme.dart';
import 'package:sitesync/View/Screens/Dashboard/Dashboard.dart';
import 'package:sitesync/View/Screens/Task/overall_taskscreen.dart';
import 'package:sitesync/View/util/FilePickingInput.dart';
import 'package:sitesync/service_class/BackendService.dart';
import 'package:sitesync/service_class/connectionService.dart';
import 'package:shared_preferences/shared_preferences.dart';

import '../../../Components/Snackbars.dart';
import '../../../service_class/helperClass.dart';

class CreatetaskScreen extends StatefulWidget {
  const CreatetaskScreen({super.key});

  @override
  State<CreatetaskScreen> createState() => _CreatetaskScreenState();
}

class _CreatetaskScreenState extends State<CreatetaskScreen> {
  String fileNames = '';
  Uint8List? fileBytes;
  String? _selectedFiles;
  var taskTitle = TextEditingController();
  var choosenProject = null;
  int? choosenProjectid;
  var choosenCategory = null;
  int? choosenCategoryid;
  var choosenSubCategory = null;
  int? choosensubCategoryid;
  var choosenAssignto = null;
  int? Assignto_roleid;
  TextEditingController taskDescription = TextEditingController();
  var taskAdditionalInformation = TextEditingController();
  var taskStartDate = TextEditingController();
  var taskStarttime = TextEditingController();
  var taskEndDate = TextEditingController();
  var taskEndTime = TextEditingController();
  String selectedChoice = ''; // Default selection
  List<String> categoryList = [];
  Map<String, int> categoryMap = {};
  List<String> projectList = [];
  Map<String, int> projectMap = {};
  Map<String, int> AssigntoMap = {};
  List<String> subcategoryList = [];
  Map<String, int> subcategoryMap = {};
  bool loading = false;
  final Map<String, List<String>> categorizedItems = {};
  List<String> AssigntoList = [];
  final _createtaskkey = GlobalKey<FormState>();

  @override
  void initState() {
    super.initState();
    Projectlist();
    getcategory();
  }

  Uint8List generateDummyBytes() {
    const int dummySize = 1024;
    List<int> dummyData = List<int>.generate(dummySize, (index) => index % 256);
    return Uint8List.fromList(dummyData);
  }

  Future<Uint8List?> getFileBytes(String filePath) async {
    try {
      File file = File(filePath);
      return await file.readAsBytes();
    } catch (e) {
      print("Error reading file: $e");
      return null;
    }
  }

  void _pickFile() async {
    FilePickerResult? result = await FilePicker.platform.pickFiles(
      allowMultiple: false,
    );

    if (result != null && result.files.isNotEmpty) {
      PlatformFile file = result.files.first;
      Uint8List? bytes = await getFileBytes(file.path!);

      if (bytes != null) {
        setState(() {
          _selectedFiles = file.path;
          fileNames = file.name;
          fileBytes = bytes;
        });
      }
    } else {
      setState(() {
        fileBytes = generateDummyBytes();
      });
    }
  }

  getAssigntolist(int projectid) {
    print(ConnectionService.assignto);
    Backendservice.function(
            {'project_id': projectid}, ConnectionService.assignto, "POST")
        .then((result) async {
      print(result);
      List<Map<String, dynamic>> data =
          List<Map<String, dynamic>>.from(result['data']);

      setState(() {
        AssigntoList = data.map((item) => item['name'].toString()).toList();
        AssigntoMap = {
          for (var item in data) item['name'].toString(): item['id']
        };
        print(AssigntoList);
      });
    });
  }

  getcategory() {
    Backendservice.function({}, ConnectionService.getcategory, "POST")
        .then((result) async {
      List<Map<String, dynamic>> data =
          List<Map<String, dynamic>>.from(result['data']);

      setState(() {
        categoryList = data.map((item) => item['category'].toString()).toList();
        categoryMap = {
          for (var item in data) item['category'].toString(): item['id']
        };
      });
    });
  }

  Projectlist() {
    Backendservice.function({}, ConnectionService.projectList, "POST")
        .then((result) async {
      List<Map<String, dynamic>> data =
          List<Map<String, dynamic>>.from(result['data']);
      setState(() {
        projectList =
            data.map((item) => item['project_name'].toString()).toList();
        projectMap = {
          for (var item in data) item['project_name'].toString(): item['id']
        };
      });
    });
  }

  getsubcategory(int catid) {
    Backendservice.function(
            {}, "${ConnectionService.getsubcategory}/$catid", "POST")
        .then((result) async {
      List<Map<String, dynamic>> data =
          List<Map<String, dynamic>>.from(result['data']);

      setState(() {
        subcategoryList =
            data.map((item) => item['sub_category'].toString()).toList();
        subcategoryMap = {
          for (var item in data) item['sub_category'].toString(): item['id']
        };
      });
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: iconAppBarBackgroundless(
          title: Text(
        "Create Task",
        style: TextStyle(
          fontSize: 20.r,
        ),
      )),
      body: SafeArea(
        child: SingleChildScrollView(
          child: Padding(
            padding: EdgeInsets.symmetric(horizontal: 20.r, vertical: 20.r),
            child: Form(
              key: _createtaskkey,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  DropdownInput(
                      LabelText: "Project",
                      hintText: "Select Option",
                      validatorText: "Select Project",
                      dropdownValue: choosenProject,
                      dropdownList: projectList,
                      onChanged: (value) {
                        setState(() {
                          choosenProject = value!;
                          choosenProjectid = projectMap[choosenProject];
                          print("Selected Project: $choosenProject");
                          print("Selected ID: ${projectMap[choosenProject]}");
                        });
                        getAssigntolist(choosenProjectid!);
                      }),
                  SizedBox(height: 5.r),
                  TextInput(
                      LabelText: "Task Title",
                      Controller: taskTitle,
                      HintText: "Enter Task Title",
                      ValidatorText: "Please Enter Task Title",
                      onChanged: (value) {}),
                  SizedBox(
                    height: 5.r,
                  ),
                  DropdownInput(
                      LabelText: "Assign To",
                      hintText: "Select Option",
                      validatorText: "Select assign to",
                      dropdownValue: choosenAssignto,
                      dropdownList: AssigntoList,
                      onChanged: (value) {
                        setState(() {
                          choosenAssignto = value!;
                          Assignto_roleid = AssigntoMap[choosenAssignto];
                        });
                      }),
                  // CustomMultiSelectDropdown(
                  //     items: AssigntoList,
                  //     selectedItems: const [],
                  //     initialvalue: choosen_AssigningTeam,
                  //     onSelectionChanged: (value) {
                  //       choosen_AssigningTeam = value;
                  //       Assignto_roleid = choosen_AssigningTeam
                  //           .map((item) => AssigntoMap[item])
                  //           .toList();
                  //       print("Selected assignto: $choosen_AssigningTeam");
                  //       print("Selected assignto id: $Assignto_roleid");
                  //     },
                  //     LabelText: "Assign To",
                  //     key: assignedtoPersonInDDkey),
                  SizedBox(height: 5.r),
                  Row(
                    children: [labelTitle("Task Priority"), labelStar(true)],
                  ),
                  SizedBox(height: 3.r),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.start,
                    children: [
                      ChoiceChip(
                        padding: EdgeInsets.symmetric(
                            horizontal: 22.r, vertical: 8.r),
                        label: Text("Low"),
                        labelStyle: TextStyle(
                            color: selectedChoice == 'Low' ? kLow : blackColor),
                        checkmarkColor: kLow,
                        selected: selectedChoice == 'Low',
                        selectedColor: kBGLow,
                        backgroundColor: greyColor20,
                        onSelected: (selected) {
                          if (selected) {
                            setState(() {
                              selectedChoice = 'Low';
                            });
                          }
                        },
                      ),
                      SizedBox(width: 15.r),
                      ChoiceChip(
                        padding: EdgeInsets.symmetric(
                            horizontal: 10.r, vertical: 8.r),
                        label: Text("Medium"),
                        labelStyle: TextStyle(
                            color: selectedChoice == 'Medium'
                                ? kMedium
                                : blackColor),
                        checkmarkColor: kMedium,
                        backgroundColor: greyColor20,
                        selected: selectedChoice == 'Medium',
                        selectedColor: kBGMedium,
                        onSelected: (selected) {
                          if (selected) {
                            setState(() {
                              selectedChoice = 'Medium';
                            });
                          }
                        },
                      ),
                      SizedBox(width: 15.r),
                      ChoiceChip(
                        padding: EdgeInsets.symmetric(
                            horizontal: 22.r, vertical: 8.r),
                        label: Text("High"),
                        labelStyle: TextStyle(
                            color:
                                selectedChoice == "High" ? kHigh : blackColor),
                        checkmarkColor: kHigh,
                        selected: selectedChoice == "High",
                        selectedColor: kBGHigh,
                        backgroundColor: greyColor20,
                        onSelected: (selected) {
                          if (selected) {
                            setState(() {
                              selectedChoice = "High";
                            });
                          }
                        },
                      ),
                    ],
                  ),
                  SizedBox(height: 5.r),
                  DescriptionInput(
                      validationRequired: false,
                      LabelText: "Task Description",
                      Controller: taskDescription,
                      HintText: "Enter task Description",
                      ValidatorText: "Please Enter Task Description",
                      onChanged: (value) {}),
                  //SizedBox(height: 5.r),
                  // DescriptionInput(
                  //     validationRequired: false,
                  //     LabelText: "Additional Description",
                  //     Controller: taskAdditionalInformation,
                  //     HintText: "Enter Additional Description",
                  //     ValidatorText: "Please Enter Additional Description",
                  //     onChanged: (value) {}),
                  // SizedBox(height: 5.r),
                  // Row(
                  //   mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  //   children: [
                  //     SizedBox(
                  //       width: Get.width / 2.5,
                  //       child: DurationDateInput(
                  //           Controller: taskStartDate,
                  //           LabelText: "Start Date",
                  //           HintText: "DD-MM-YYYY",
                  //           ValidatorText: "Select Date"),
                  //     ),
                  //     SizedBox(
                  //         width: Get.width / 2.5,
                  //         child: TimeInput(
                  //             controller: taskStarttime,
                  //             labelText: "Start Time",
                  //             hintText: "HH:MM",
                  //             validatorText: "Select Time"))
                  //   ],
                  // ),
                  SizedBox(height: 5.r),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      SizedBox(
                        width: Get.width / 2.5,
                        child: DurationDateInput(
                            startDateController: taskStartDate,
                            Controller: taskEndDate,
                            LabelText: "End Date",
                            HintText: "DD-MM-YYYY",
                            ValidatorText: "Select Date"),
                      ),
                      // SizedBox(
                      //     width: Get.width / 2.5,
                      //     child: TimeInput(
                      //         controller: taskEndTime,
                      //         labelText: "End Time",
                      //         hintText: "HH:MM",
                      //         validatorText: "Select Time"))
                    ],
                  ),
                  SizedBox(height: 15.r),
                  //new file picker
                  FilepickingInputNew(
                    onFilePicked: (path, name) {
                      setState(() {
                        _selectedFiles = path;
                        fileNames = name;
                      });
                    },
                  ),
                  SizedBox(height: 50.r),
                  Align(
                    alignment: Alignment.center,
                    child: SizedBox(
                        width: Get.width / 2,
                        child: ElevatedButton(
                            onPressed: loading
                                ? null
                                : () async {
                                    if (_createtaskkey.currentState!
                                            .validate() &&
                                        selectedChoice.isNotEmpty) {
                                      setState(() {
                                        loading = true;
                                      });

                                      Map<String, dynamic> userdetails = {
                                        't_type': "fresh",
                                        'title': taskTitle.text.toString(),
                                        'project_id': choosenProjectid,
                                        'assigned_to': Assignto_roleid,
                                        // 'category_id': choosenCategoryid,
                                        // 'sub_category_id': choosensubCategoryid,
                                        'priority': selectedChoice.toString(),
                                        'description':
                                            taskDescription.text.toString(),
                                        // 'additional_info':
                                        //     taskAdditionalInformation.text
                                        //         .toString(),
                                        // 'startdate': Helperclass.dateFormatServer(
                                        //     taskStartDate.text.toString()),
                                        // 'starttime': Helperclass.timeFormatServer(
                                        //     taskStarttime.text.toString()),
                                        'enddate': Helperclass.dateFormatServer(
                                            taskEndDate.text.toString()),
                                        'endtime': Helperclass.timeFormatServer(
                                            taskEndTime.text.toString()),
                                        'file_attachment': _selectedFiles
                                      };
                                      print(userdetails);
                                      Backendservice.UploadFiles(
                                              userdetails,
                                              ConnectionService.createtask,
                                              "POST")
                                          .then((result) async {
                                        if (result['success'] == true) {
                                          Get.off(() =>
                                              DashboardScreen(initialIndex: 3));
                                          CustomSnackbar(
                                            "Task Added Successfull",
                                            "Task has been assigned to person selected",
                                          );
                                        }
                                      }).catchError((error) {
                                        print(
                                            "Error occurred during login: $error");
                                        CustomErrorSnackbar();
                                        setState(() {
                                          loading = false;
                                        });
                                        const SizedBox(
                                          height: 20,
                                        );
                                      }).whenComplete(() => setState(() {
                                                loading = false;
                                              }));
                                    } else {
                                      setState(() {
                                        loading = false;
                                      });
                                    }
                                  },
                            child: loading
                                ? SizedBox(
                                    height: 16,
                                    width: 16,
                                    child: CircularProgressIndicator(
                                      color: Colors.white,
                                      strokeWidth: 2,
                                    ),
                                  )
                                : Text("Create Task"))),
                  ),
                  SizedBox(height: 100.r),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }

  // Widget filePickingInput() {
  //   final screenWidth = MediaQuery.of(context).size.width;
  //   return Column(
  //     crossAxisAlignment: CrossAxisAlignment.start,
  //     children: [
  //       labelTitle(" Files"),
  //       Container(
  //         height: 50.r,
  //         decoration: BoxDecoration(
  //             color: greyColor10, borderRadius: BorderRadius.circular(10)),
  //         child: Row(
  //           children: [
  //             SizedBox(
  //               child: TextButton(
  //                 style: ButtonStyle(
  //                   foregroundColor:
  //                       WidgetStateProperty.all<Color>(Colors.white),
  //                   backgroundColor:
  //                       WidgetStateProperty.all<Color>(kPrimaryColor),
  //                   shape: WidgetStateProperty.all<RoundedRectangleBorder>(
  //                       RoundedRectangleBorder(
  //                     borderRadius: BorderRadius.circular(5.r),
  //                   )),
  //                 ),
  //                 onPressed: _pickFile,
  //                 child: Text(
  //                   "Choose File",
  //                   style: TextStyle(
  //                     color: Colors.white,
  //                     fontSize: 14.r,
  //                     fontWeight: FontWeight.bold,
  //                   ),
  //                 ),
  //               ),
  //             ),
  //             fileNames != null
  //                 ? Padding(
  //                     padding: EdgeInsets.symmetric(horizontal: 5.r),
  //                     child: SizedBox(
  //                       width: screenWidth / 2,
  //                       child: Text(
  //                         fileNames!,
  //                         style: TextStyle(
  //                           fontSize: 18.r,
  //                         ),
  //                         textAlign: TextAlign.center,
  //                         overflow: TextOverflow.ellipsis,
  //                       ),
  //                     ),
  //                   )
  //                 : Padding(
  //                     padding: const EdgeInsets.symmetric(horizontal: 30),
  //                     child: Text(
  //                       'No Files Added',
  //                       style: TextStyle(
  //                         fontSize: 18.r,
  //                       ),
  //                       textAlign: TextAlign.center,
  //                       overflow: TextOverflow.ellipsis,
  //                     ),
  //                   )
  //           ],
  //         ),
  //       ),
  //     ],
  //   );
  // }
}