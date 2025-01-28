import { useForm } from "@inertiajs/react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { useState } from "react";
import Swal from "sweetalert2";

export default function Create({ departments }) {
    const [successMessage, setSuccessMessage] = useState();

    const [errorMessage, setErrorMessage] = useState();

    const { data, setData, post, errors, processing } = useForm({
        first_name: "",
        last_name: "",
        dept_no: "",
        birth_date: "",
        hire_date: "",
        gender: "",
        photo: null,
    });

    const handleSubmit = (e) => {
        //backend
        e.preventDefault();
        const formData = new FormData();
        formData.append("first_name", data.first_name);
        formData.append("last_name", data.last_name);
        formData.append("dept_no", data.dept_no);
        formData.append("birth_date", data.birth_date);
        formData.append("hire_date", data.hire_date);
        formData.append("gender", data.gender);

        if (data.photo) {
            formData.append("photo", data.photo);
        }

        post(route("employee.store"), {
            data: formData,
            headers: {
                "Content-Type": "multipart/form-data",
            },
            onSuccess: () => {
                Swal.fire({
                    icon: "success",
                    title: "สำเร็จ!",
                    text: "สร้างพนักงานสำเร็จ!",
                });
                setSuccessMessage("Employee created successfully!");
            },
            onError: () => {
                Swal.fire({
                    icon: "error",
                    title: "เกิดข้อผิดพลาด!",
                    text: "ไม่สามารถสร้างพนักงานได้ กรุณาลองอีกครั้ง",
                });
                setErrorMessage("An error occurred while creating employee. Please try again.");
            },
        });

    };

    return (
        <AuthenticatedLayout>
            <div className="max-w-lg mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
                {/* แสดงข้อความ Success */}
                {successMessage && (
                    <div className="bg-green-100 text-green-800 p-4 rounded mb-4">
                        {successMessage}
                    </div>
                )}

                {/* แสดงข้อความ Error */}
                {errorMessage && (
                    <div className="bg-red-100 text-red-800 p-4 rounded mb-4">
                        {errorMessage}
                    </div>
                )}
                <h2 className="text-2xl font-bold text-center mb-6">
                    Add Employee
                </h2>
                <form onSubmit={handleSubmit} className="space-y-4">
                    <div>
                        <label className="block text-gray-700 font-medium">
                            First Name
                        </label>
                        <input
                            type="text"
                            value={data.first_name}
                            onChange={(e) =>
                                setData("first_name", e.target.value)
                            }
                            maxLength="14"
                            required
                            className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                        {errors.first_name && (
                            <span className="text-red-500 text-sm">
                                {errors.first_name}
                            </span>
                        )}
                    </div>
                    <div>
                        <label className="block text-gray-700 font-medium">
                            Last Name
                        </label>
                        <input
                            type="text"
                            value={data.last_name}
                            onChange={(e) =>
                                setData("last_name", e.target.value)
                            }
                            maxLength="16"
                            required
                            className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                        {errors.last_name && (
                            <span className="text-red-500 text-sm">
                                {errors.last_name}
                            </span>
                        )}
                    </div>
                    <div>
                        <label className="block text-gray-700 font-medium">
                            Department
                        </label>
                        <select
                            value={data.dept_no}
                            onChange={(e) => setData("dept_no", e.target.value)}
                            required
                            className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="" disabled>
                                Please select department
                            </option>
                            {departments.map((dept) => (
                                <option key={dept.dept_no} value={dept.dept_no}>
                                    {dept.dept_name}
                                </option>
                            ))}
                        </select>

                        {errors.dept_no && (
                            <span className="text-red-500 text-sm">
                                {errors.dept_no}
                            </span>
                        )}
                    </div>
                    <div>
                        <label className="block text-gray-700 font-medium">
                            Birth Date
                        </label>
                        <input
                            type="date"
                            value={data.birth_date}
                            onChange={(e) =>
                                setData("birth_date", e.target.value)
                            }
                            required
                            className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                        {errors.birth_date && (
                            <span className="text-red-500 text-sm">
                                {errors.birth_date}
                            </span>
                        )}
                    </div>
                    <div>
                        <label className="block text-gray-700 font-medium">
                            Hire Date
                        </label>
                        <input
                            type="date"
                            value={data.hire_date}
                            onChange={(e) =>
                                setData("hire_date", e.target.value)
                            }
                            className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                        {errors.hire_date && (
                            <span className="text-red-500 text-sm">
                                {errors.hire_date}
                            </span>
                        )}
                    </div>
                    <div>
                        <label className="block text-gray-700 font-medium">
                            Gender
                        </label>
                        <select
                            value={data.gender}
                            onChange={(e) => setData("gender", e.target.value)}
                            required
                            className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="" disabled>
                                Please select gender
                            </option>
                            <option value="M">Male</option>
                            <option value="F">Female</option>
                        </select>

                        {errors.gender && (
                            <span className="text-red-500 text-sm">
                                {errors.gender}
                            </span>
                        )}
                    </div>
                    <div>
                        <label className="block text-sm font-medium text-gray-700">
                            Photo:
                        </label>
                        <input
                            type="file"
                            accept="image/*"
                            onChange={(e) =>
                                setData("photo", e.target.files[0])
                            }
                            className="mt-1 p-2 block w-full border rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        />
                        {errors.photo && (
                            <span className="text-red-500 text-sm">
                                {errors.photo}
                            </span>
                        )}
                    </div>

                    <button
                        type="submit"
                        className="w-full bg-blue-500 text-white font-medium py-2 px-4 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        disabled={processing}
                    >
                        {processing ? (
                            <span className="flex items-center justify-center">
                                <svg
                                    className="animate-spin h-5 w-5 mr-2"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <circle
                                        className="opacity-25"
                                        cx="12"
                                        cy="12"
                                        r="10"
                                        stroke="currentColor"
                                        strokeWidth="4"
                                    ></circle>
                                    <path
                                        className="opacity-75"
                                        fill="currentColor"
                                        d="M4 12a8 8 0 018-8v8h8a8 8 0 01-8 8v-8H4z"
                                    ></path>
                                </svg>
                                Processing...
                            </span>
                        ) : (
                            "Create"
                        )}
                    </button>
                </form>
            </div>
        </AuthenticatedLayout>
    );
}