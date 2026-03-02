CREATE VIEW student_course_details AS
SELECT
    s.student_id,
    s.student_name,
    s.major,
    c.course_id,
    c.course_name,
    c.credits,
    e.semester,
    e.grade
FROM enrollments e
JOIN students s ON e.student_id = s.student_id
JOIN courses c ON e.course_id = c.course_id;
