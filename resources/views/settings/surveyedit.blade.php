@extends ('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/settingsprofile.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">

    <div class="body-div px-4 py-1 mb-3">
        <div class="body-head">
            <div class="body-h6">
                <h6 class="head1h6"><a href="{{ route('dashboard.index') }}">Dashboard /</a></h6>
                <h6 class="head1h6"><a href="{{ route('settings.index') }}">Settings /</a></h6>
                <h6 class="head2h6"><a href="{{ route('settings.surveyedit', $formattedSurvey['survey_id']) }}">Edit
                        Survey</a></h6>
            </div>
        </div>

        <div class="container-fluid px-0">
            <form action="">
                <div class="row mt-3">
                    <div class="col-sm-12 col-md-12 col-xl-12 pe-2 settingleft">
                        <div class="settingform-div mb-2">
                            <div class="settingheader">
                                <div>
                                    <h5 class="mb-2">Basic Information</h5>
                                    <h6 class="mb-0">Survey to gather feedback on construction project quality,
                                        timelines, and customer satisfaction for improvements.</h6>
                                </div>
                            </div>
                            <div class="settingform">
                                <input type="hidden" name="survey_id" id="survey_id"
                                    value="{{ $formattedSurvey['survey_id'] }}">
                                <div class="container-fluid p-0 form-div border-0">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 col-xl-12 mb-3 pe-2 inputs">
                                            <label for="title">Survey Title</label>
                                            <input type="text" class="form-control" name="title" id="title"
                                                value="{{ $formattedSurvey['title'] }}" required>
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-xl-12 mb-3 pe-2 inputs">
                                            <label for="description">Survey Description</label>
                                            <textarea rows="3" class="form-control" name="description" id="description" required>{{ $formattedSurvey['description'] }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-12 col-xl-12 pe-2 settingright">
                        <div class="settingform-div mb-2">
                            <div class="settingheader">
                                <div>
                                    <h5 class="mb-2">Add Survey Questions</h5>
                                    <h6 class="mb-0">This section allows you to create and customize all the
                                        questions for your survey, including choosing question types and setting
                                        up
                                        answer options.</h6>
                                </div>
                                <div>
                                    <button type="button" class="settingheadbtn add_questions_btn">+ Add Questions</button>
                                </div>
                            </div>

                            <div class="settingform">
                                <div class="container-fluid p-0 form-div border-0">
                                    <div class="row">
                                        @foreach ($formattedSurvey['questions'] as $question)
                                            @if ($question['question_type'] == 'Text' or $question['question_type'] == 'location')
                                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3 pe-2 inputs question_parent">
                                                    <div class="qstflex d-flex justify-content-between align-items-center">
                                                        <label for="qst2">{{ $question['question'] }}</label>
                                                        <i class="fas fa-trash-alt text-danger question_delete"
                                                            data_id="{{ $question['question_id'] }}"></i>
                                                    </div>
                                                    <input type="text" class="form-control" disabled></i>
                                                </div>
                                            @endif

                                            @if ($question['question_type'] == 'Textarea')
                                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3 pe-2 inputs question_parent">
                                                    <div class="qstflex d-flex justify-content-between align-items-center">
                                                        <label for="qst2">{{ $question['question'] }}</label>
                                                        <i class="fas fa-trash-alt text-danger question_delete"
                                                            data_id="{{ $question['question_id'] }}"></i>
                                                    </div>
                                                    <textarea rows="3" class="form-control" disabled></textarea>
                                                </div>
                                            @endif

                                            @if ($question['question_type'] == 'Radio')
                                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3 pe-2 inputs question_parent">
                                                    <div class="qstflex d-flex justify-content-between align-items-center">
                                                        <label for="qst">{{ $question['question'] }}</label>
                                                        <i class="fas fa-trash-alt text-danger question_delete"
                                                            data_id="{{ $question['question_id'] }}"></i>
                                                    </div>
                                                    <div class="d-block form-check ps-1">
                                                        @foreach ($question['choices'] as $choice)
                                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                                <input type="radio" disabled>
                                                                <label class="mb-0" for="radio1">
                                                                    {{ $choice['choice'] }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($question['question_type'] == 'Checkbox')
                                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3 pe-2 inputs question_parent">
                                                    <div class="qstflex d-flex justify-content-between align-items-center">
                                                        <label for="qst">{{ $question['question'] }}</label>
                                                        <i class="fas fa-trash-alt text-danger question_delete"
                                                            data_id="{{ $question['question_id'] }}"></i>
                                                    </div>
                                                    <div class="d-block form-check ps-1">
                                                        @foreach ($question['choices'] as $choice)
                                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                                <input type="checkbox" disabled>
                                                                <label class="mb-0" for="radio1">
                                                                    {{ $choice['choice'] }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($question['question_type'] == 'File')
                                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3 pe-2 inputs question_parent">
                                                    <div class="qstflex d-flex justify-content-between align-items-center">
                                                        <label for="qst2">{{ $question['question'] }}</label>
                                                        <i class="fas fa-trash-alt text-danger question_delete"
                                                            data_id="{{ $question['question_id'] }}"></i>
                                                    </div>
                                                    <input type="file" class="form-control" disabled></i>
                                                </div>
                                            @endif
                                            {{-- @if ($question['question_type'] == 'Date&Time')
                                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3 pe-2 inputs question_parent">
                                                    <div class="qstflex d-flex justify-content-between align-items-center">
                                                        <label for="qst2">{{ $question['question'] }}</label>
                                                        <i class="fas fa-trash-alt text-danger question_delete"
                                                            data_id="{{ $question['question_id'] }}"></i>
                                                    </div>
                                                    <input type="date" class="form-control" disabled></i>
                                                </div>
                                            @endif --}}
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-12 d-flex justify-content-center align-items-center mt-3">
                        <button type="button" class="formbtn update_btn">Update</button>
                    </div>
                </div>
            </form>
        </div>

    </div>

    <!-- Add Survey Modal -->
    <div class="modal fade" id="surveyqst" tabindex="-1" aria-labelledby="surveyqstLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <div class="usericon">
                        <h5 class="mb-0"><i class="fa-solid fa-file-pen"></i></h5>
                    </div>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4 class="modal-title mb-2 fs-5" id="surveyqstLabel">Create Survey Question</h4>


                    <div class="col-sm-12 col-md-12 mt-3 mb-2">
                        <button type="button" class="qstbtn w-100">+ Add a new question</button>
                    </div>

                    <form id="question_form">
                        <div class="survey-container row form-div py-1 px-2 mb-1">
                            <div class="col-sm-12 col-md-12 mb-1">
                                <label for="question" class="col-form-label">Question</label>
                                <input type="text" class="form-control question" name="question[]" required>
                            </div>
                            <div class="col-sm-12 col-md-12 mb-1">
                                <label for="contact" class="col-form-label">Answer Type</label>
                                <select name="answer_type[]" class="form-select answer_type" required>
                                    <option value="" selected disabled>Select Options</option>
                                    <option value="Checkbox">Checkbox</option>
                                    <option value="Radio">Radio</option>
                                    <option value="Text">Text</option>
                                    <option value="Textarea">Textarea</option>
                                    {{-- <option value="Date&Time">Date&Time</option> --}}
                                    <option value="File">File</option>
                                    <option value="location">location</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-12 mb-1" id="choices-container">
                                <label for="choices" class="col-form-label">Choices</label>
                                <div class="mb-1">
                                    <input type="text" class="form-control choices" name="choices[]">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center newdelbtn my-1">
                                <label class="addnew text-primary" style="cursor: pointer;">+ Add New</label>
                            </div>
                        </div>
                    </form>

                    <div id="survey-list"></div>

                    <div class="d-flex justify-content-center align-items-center mx-auto mt-3">
                        <button type="button" class="modalbtn save_btn">Save</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Script -->
    <script>
        $(document).ready(function() {
            function toggleAddNewLabel(selectElement) {
                var selectedValue = selectElement.val();
                var addNewLabel = selectElement.closest('.survey-container').find('.addnew');
                var choicesContainer = selectElement.closest('.survey-container').find('#choices-container');

                if (selectedValue === 'Checkbox' || selectedValue === 'Radio') {
                    addNewLabel.show();
                    choicesContainer.show();
                } else {
                    addNewLabel.hide();
                    choicesContainer.hide();
                }
            }

            $(document).on('change', '.answer_type', function() {
                toggleAddNewLabel($(this));
            });

            let deletedQuestionIds = [];
            $(document).on('click', '.question_delete', function() {
                if (confirm('Are you sure you want to delete this question?')) {

                    var thisElement = this;
                    let postData = {
                        _token: "{{ csrf_token() }}",
                        id: $(this).attr('data_id'),
                    };

                    $.ajax({
                        url: "{{ route('settings.surveyquestiondelete') }}",
                        type: 'POST',
                        data: JSON.stringify(postData),
                        contentType: 'application/json',
                        success: function(response) {
                            $(thisElement).closest('.question_parent').remove();
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            alert('Error saving survey!');
                        }
                    });
                }
            });

            $(document).on('click', '.update_btn', function() {
                let title = $('#title').val();
                let description = $('#description').val();
                let survey_id = $('#survey_id').val();

                let postData = {
                    _token: "{{ csrf_token() }}",
                    title: title,
                    description: description,
                    survey_id: survey_id,
                };

                $.ajax({
                    url: "{{ route('settings.surveyupdate') }}",
                    type: 'POST',
                    data: JSON.stringify(postData),
                    contentType: 'application/json',
                    success: function(response) {
                        location.href = "{{ route('settings.survey') }}";
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        alert('Error saving survey!');
                    }
                });
            });

            $(document).on('click', '.save_btn', function() {
                let surveyData = [];
                let title = $('#title').val();
                let description = $('#description').val();
                let survey_id = $('#survey_id').val();

                let choices1 = [];
                let question1 = $('#question_form').find('.question').val();
                let question_type1 = $('#question_form').find('.answer_type').val();
                $('#question_form').find('.choices').each(function() {
                    let choiceValue = $(this).val().trim();
                    if (choiceValue !== '') {
                        choices1.push(choiceValue);
                    }
                });

                surveyData.push({
                    question: question1,
                    question_type: question_type1,
                    choices: choices1
                });

                $('#survey-list .survey-container').each(function() {
                    let choices = [];
                    let question = $(this).find('.question').val();
                    let question_type = $(this).find('.answer_type').val();

                    // Get all choices
                    $(this).find('.choices').each(function() {
                        let choiceValue = $(this).val().trim();
                        if (choiceValue !== '') {
                            choices.push(choiceValue);
                        }
                    });

                    // Push each survey question as an object
                    surveyData.push({
                        question: question,
                        question_type: question_type,
                        choices: choices
                    });
                });

                let postData = {
                    _token: "{{ csrf_token() }}",
                    title: title,
                    description: description,
                    survey_id: survey_id,
                    questions: surveyData
                };

                console.log('Submitting Data:', postData); // Debugging purpose

                // AJAX call
                $.ajax({
                    url: "{{ route('settings.surveystore') }}", // Change this to your server-side URL
                    type: 'POST',
                    data: JSON.stringify(postData), // Convert to JSON string
                    contentType: 'application/json', // Set content type
                    success: function(response) {
                        location.href = response.redirect_url;
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        alert('Error saving survey!');
                    }
                });
            });

            $(document).on('click', '.add_questions_btn', function() {
                var title = $('#title').val();
                if (title != "") {
                    $('#surveyqst').modal('show');
                    $('#title-error').text('');
                } else {
                    $('#title').focus();
                    $('#title-error').text('Survey Title is required');
                }
            });

            $('.qstbtn').click(function() {
                var newSurvey = $('.survey-container').first().clone().removeAttr('id').show();
                newSurvey.find('input, select').val('');
                newSurvey.find('.newdelbtn').append(
                    '<i class="fas fa-trash-alt text-danger delete-question d-flex justify-content-end align-items-center" style="cursor: pointer;"></i>'
                );
                $('#survey-list').append(newSurvey);
            });

            $(document).on('click', '.addnew', function() {
                var newChoiceGroup = $('<div class="inpflex mb-1">')
                    .append('<input type="text" class="form-control border-0 choices" name="choices[]" >')
                    .append('<i class="fas fa-trash-alt delete-icon" style="cursor: pointer;"></i>');
                $(this).closest('.survey-container').find('#choices-container').append(newChoiceGroup);
                newChoiceGroup.find('.delete-icon').show();
            });

            $(document).on('click', '.delete-icon', function() {
                $(this).closest('.inpflex').remove();
            });

            $(document).on('click', '.delete-question', function() {
                $(this).closest('.survey-container').remove();
            });

            $('.answer_type').each(function() {
                toggleAddNewLabel($(this));
            });
        });
    </script>
@endsection
