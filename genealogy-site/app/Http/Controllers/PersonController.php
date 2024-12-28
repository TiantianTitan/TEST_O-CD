<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PersonController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

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
            'first_name' => 'required|string|max:255', // 必填，最大长度255
            'last_name' => 'required|string|max:255', // 必填，最大长度255
            'middle_names' => 'nullable|string|max:255', // 可选，最大长度255
            'birth_name' => 'nullable|string|max:255', // 可选，最大长度255
            'date_of_birth' => 'nullable|date', // 可选，必须是有效日期格式
        ]);
    
        // 格式化字段
        $validated['created_by'] = auth()->id(); // 设置创建者为当前用户的 ID
    
        // First Name: 首字母大写，其他字母小写
        $validated['first_name'] = ucfirst(strtolower($validated['first_name']));
    
        // Middle Names: 每个中间名首字母大写，其余小写；如果为空，则为 NULL
        if (!empty($validated['middle_names'])) {
            $validated['middle_names'] = collect(explode(',', $validated['middle_names']))
                ->map(fn($name) => ucfirst(strtolower(trim($name)))) // 去除多余空格并格式化
                ->implode(', ');
        } else {
            $validated['middle_names'] = null;
        }
    
        // Last Name: 全部大写
        $validated['last_name'] = strtoupper($validated['last_name']);
    
        // Birth Name: 如果为空，则设置为 Last Name；全部大写
        if (empty($validated['birth_name'])) {
            $validated['birth_name'] = $validated['last_name'];
        } else {
            $validated['birth_name'] = strtoupper($validated['birth_name']);
        }
    
        // Date of Birth: 如果为空，则设置为 NULL（数据库支持）
        $validated['date_of_birth'] = $validated['date_of_birth'] ?? null;
    
        // 保存数据
        Person::create($validated);
    
        // 重定向到人物列表页，并显示成功消息
        return redirect()->route('people.index')->with('success', 'Person created successfully!');
    }




}
