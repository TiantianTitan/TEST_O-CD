<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    // 显示所有人及其创建者
    public function index()
    {
        // 查询所有人，并加载创建者的名称
        $people = Person::with('creator')->get();

        return view('people.index', compact('people'));
    }

    // 显示某个人的详情，包括其父母和子女
    public function show($id)
    {
        $person = Person::with(['parents.parent', 'children.child'])->findOrFail($id);

        return view('people.show', compact('person'));
    }


    // 显示创建新人的表单
    public function create()
    {
        return view('people.create');
    }

    // 保存新人的信息
    public function store(Request $request)
    {
        // 验证请求数据
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_names' => 'nullable|string|max:255',
            'birth_name' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
        ]);

        // 格式化数据
        $validated['created_by'] = auth()->id(); // 由当前用户创建
        $validated['first_name'] = ucfirst(strtolower($validated['first_name'])); // 首字母大写
        $validated['last_name'] = strtoupper($validated['last_name']); // 全部大写
        $validated['middle_names'] = $validated['middle_names'] 
            ? collect(explode(',', $validated['middle_names']))
                ->map(fn($name) => ucfirst(strtolower(trim($name))))
                ->implode(', ')
            : null;
        $validated['birth_name'] = $validated['birth_name'] ?? $validated['last_name'];

        // 保存到数据库
        Person::create($validated);

        return redirect()->route('people.index')->with('success', 'Person created successfully!');
    }




}
