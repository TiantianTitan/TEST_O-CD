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



    // public function proposeModification(Request $request, $id)
    // {
    //     $request->validate([
    //         'type' => 'required|in:update_person,add_relationship',
    //         'content' => 'required|array',
    //     ]);

    //     $modification = Modification::create([
    //         'target_id' => $id,
    //         'proposed_by' => auth()->id(),
    //         'type' => $request->type,
    //         'content' => json_encode($request->content),
    //     ]);

    //     return redirect()->back()->with('success', 'Modification proposal submitted successfully!');
    // }

    public function update(Request $request, $id)
    {
        $person = Person::findOrFail($id);
    
        // 验证输入数据
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_name' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'parents' => 'nullable|array',
            'children' => 'nullable|array',
        ]);
    
        // 更新基本信息
        $person->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'birth_name' => $validated['birth_name'],
            'date_of_birth' => $validated['date_of_birth'],
        ]);
    
        // 更新父母关系
        if (isset($validated['parents'])) {
            $person->parents()->delete(); // 清除旧的父母关系
            foreach ($validated['parents'] as $parentName) {
                $parent = Person::where('first_name', explode(' ', $parentName)[0])
                                ->where('last_name', explode(' ', $parentName)[1])
                                ->first();
                if ($parent) {
                    $person->parents()->create([
                        'parent_id' => $parent->id,
                        'created_by' => auth()->id(), // 添加 created_by 字段
                    ]);
                }
            }
        }
        
    
        // 更新子女关系
        if (isset($validated['children'])) {
            $person->children()->delete(); // 清除旧的子女关系
            foreach ($validated['children'] as $childName) {
                $child = Person::where('first_name', explode(' ', $childName)[0])
                                ->where('last_name', explode(' ', $childName)[1])
                                ->first();
                if ($child) {
                    $person->children()->create([
                        'child_id' => $child->id,
                        'created_by' => auth()->id(), // 添加 created_by 字段
                    ]);
                }
            }
        }
        
    
        return redirect()->route('people.show', $person->id)->with('success', 'Person updated successfully!');
    }
    



}
