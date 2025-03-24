@extends('layouts.master')
@section('title', 'Grades Management')
@section('content')
    <div class="container mt-4">
        <h2 class="mb-3">Grades Management</h2>
        
        <!-- Terms Navigation -->
        <ul class="nav nav-tabs" id="termsNav">
            <li class="nav-item"><a class="nav-link active" href="#" onclick="changeTerm('Term 1')">Term 1</a></li>
            <li class="nav-item"><a class="nav-link" href="#" onclick="changeTerm('Term 2')">Term 2</a></li>
        </ul>

        <div class="mt-3">
            <h4 id="currentTerm">Term 1</h4>
            
            <!-- Grades Table -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Credit Hours</th>
                        <th>Grade</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="gradesTableBody"></tbody>
            </table>
            
            <button class="btn btn-primary" onclick="openGradeForm()">Add Grade</button>
            
            <h5 class="mt-3">Total CH: <span id="totalCH">0</span></h5>
            <h5>GPA: <span id="gpa">0.00</span></h5>
            <h5>Cumulative CH: <span id="cumulativeCH">0</span></h5>
            <h5>Cumulative CGPA: <span id="cgpa">0.00</span></h5>
        </div>
    </div>

    <!-- Grade Modal -->
    <div class="modal" id="gradeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Grade</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="gradeId">
                    <div class="mb-3">
                        <label class="form-label">Course</label>
                        <input type="text" id="courseName" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Credit Hours</label>
                        <input type="number" id="creditHours" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Grade</label>
                        <input type="text" id="grade" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" onclick="saveGrade()">Save</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let grades = { 'Term 1': [], 'Term 2': [] };
        let currentTerm = 'Term 1';

        function changeTerm(term) {
            currentTerm = term;
            document.getElementById("currentTerm").textContent = term;
            renderGrades();
        }

        function renderGrades() {
            const tbody = document.getElementById("gradesTableBody");
            tbody.innerHTML = "";
            let totalCH = 0, totalPoints = 0;

            grades[currentTerm].forEach(grade => {
                let gpaValue = convertGradeToPoint(grade.grade) * grade.ch;
                totalCH += grade.ch;
                totalPoints += gpaValue;
                tbody.innerHTML += `
                    <tr>
                        <td>${grade.course}</td>
                        <td>${grade.ch}</td>
                        <td>${grade.grade}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="openGradeForm(${grade.id})">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteGrade(${grade.id})">Delete</button>
                        </td>
                    </tr>`;
            });
            
            document.getElementById("totalCH").textContent = totalCH;
            document.getElementById("gpa").textContent = totalCH > 0 ? (totalPoints / totalCH).toFixed(2) : "0.00";
            calculateCumulative();
        }

        function openGradeForm(id = null) {
            const modal = new bootstrap.Modal(document.getElementById('gradeModal'));
            document.getElementById("modalTitle").textContent = id ? "Edit Grade" : "Add Grade";
            
            if (id !== null) {
                const grade = grades[currentTerm].find(g => g.id === id);
                document.getElementById("gradeId").value = grade.id;
                document.getElementById("courseName").value = grade.course;
                document.getElementById("creditHours").value = grade.ch;
                document.getElementById("grade").value = grade.grade;
            } else {
                document.getElementById("gradeId").value = "";
                document.getElementById("courseName").value = "";
                document.getElementById("creditHours").value = "";
                document.getElementById("grade").value = "";
            }
            modal.show();
        }

        function saveGrade() {
            const id = document.getElementById("gradeId").value;
            const course = document.getElementById("courseName").value;
            const ch = parseInt(document.getElementById("creditHours").value);
            const grade = document.getElementById("grade").value;
            
            if (id) {
                const existingGrade = grades[currentTerm].find(g => g.id == id);
                existingGrade.course = course;
                existingGrade.ch = ch;
                existingGrade.grade = grade;
            } else {
                grades[currentTerm].push({ id: Date.now(), course, ch, grade });
            }
            renderGrades();
            bootstrap.Modal.getInstance(document.getElementById('gradeModal')).hide();
        }

        function deleteGrade(id) {
            grades[currentTerm] = grades[currentTerm].filter(g => g.id !== id);
            renderGrades();
        }

        function calculateCumulative() {
            let totalCH = 0, totalPoints = 0;
            for (let term in grades) {
                grades[term].forEach(grade => {
                    totalCH += grade.ch;
                    totalPoints += convertGradeToPoint(grade.grade) * grade.ch;
                });
            }
            document.getElementById("cumulativeCH").textContent = totalCH;
            document.getElementById("cgpa").textContent = totalCH > 0 ? (totalPoints / totalCH).toFixed(2) : "0.00";
        }

        function convertGradeToPoint(grade) {
            const scale = { 'A': 4, 'B': 3, 'C': 2, 'D': 1, 'F': 0 };
            return scale[grade.toUpperCase()] || 0;
        }
    </script>
@endsection
