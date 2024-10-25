<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Exception;

class students extends Authenticatable implements JWTSubject
{
    protected $table = "students";
    public $timestamps = true;
    protected $primaryKey = "id";
    protected $guarded = [];

    public function getJWTIdentifier()
    {
        //getKey() 方法用于获取模型的主键值
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return ['role => students'];
    }

    // 与 company_stars 表的关联
    public function companyStars()
    {
        return $this->hasMany(company_stars::class, 'student_id', 'id');
    }

    // 与 competition_stars 表的关联
    public function competitionStars()
    {
        return $this->hasMany(competition_stars::class, 'student_id', 'id');
    }

    // 与 research_stars 表的关联
    public function researchStars()
    {
        return $this->hasMany(research_stars::class, 'student_id', 'id');
    }

    // 与 paper_stars 表的关联
    public function paperStars()
    {
        return $this->hasMany(paper_stars::class, 'student_id', 'id');
    }

    // 与 software_stars 表的关联
    public function softwareStars()
    {
        return $this->hasMany(software_stars::class, 'student_id', 'id');
    }

    // 定义静态方法用于查询逻辑
//    public static function searchStudents($searchFields)
//    {//(['companyStars', 'competitionStars', 'researchStars', 'paperStars', 'softwareStars'])
//        return self::with(['companyStars', 'competitionStars', 'researchStars', 'paperStars', 'softwareStars'])
//            ->when(!empty($searchFields['name']), function ($query) use ($searchFields) {
//                $query->where('name', 'like', '%' . $searchFields['name'] . '%');
//            })
//            ->when(!empty($searchFields['major']), function ($query) use ($searchFields) {
//                $query->where('major', 'like', '%' . $searchFields['major'] . '%');
//            })
//            ->when(!empty($searchFields['c_name']), function ($query) use ($searchFields) {
//                $query->where(function ($subQuery) use ($searchFields) {
//                    $subQuery->whereHas('competitionStars', function ($q) use ($searchFields) {
//                        $q->where('competition_name', 'like', '%' . $searchFields['c_name'] . '%');
//                    })
//                        ->orWhereHas('companyStars', function ($q) use ($searchFields) {
//                            $q->where('company_name', 'like', '%' . $searchFields['c_name'] . '%');
//                        })
//                        ->orWhereHas('researchStars', function ($q) use ($searchFields) {
//                            $q->where('project_name', 'like', '%' . $searchFields['c_name'] . '%');
//                        })
//                        ->orWhereHas('paperStars', function ($q) use ($searchFields) {
//                            $q->where('paper_title', 'like', '%' . $searchFields['c_name'] . '%');
//                        })
//                        ->orWhereHas('softwareStars', function ($q) use ($searchFields) {
//                            $q->where('software_name', 'like', '%' . $searchFields['c_name'] . '%');
//                        });
//                });
//            })
//            ->when(!empty($searchFields['status']), function ($query) use ($searchFields) {
//                $query->where(function ($subQuery) use ($searchFields) {
//                    $subQuery->whereHas('competitionStars', function ($q) use ($searchFields) {
//                        $q->where('status', 'like', '%' . $searchFields['status'] . '%');
//                    })
//                        ->orWhereHas('companyStars', function ($q) use ($searchFields) {
//                            $q->where('status', 'like', '%' . $searchFields['status'] . '%');
//                        })
//                        ->orWhereHas('researchStars', function ($q) use ($searchFields) {
//                            $q->where('status', 'like', '%' . $searchFields['status'] . '%');
//                        })
//                        ->orWhereHas('paperStars', function ($q) use ($searchFields) {
//                            $q->where('status', 'like', '%' . $searchFields['status'] . '%');
//                        })
//                        ->orWhereHas('softwareStars', function ($q) use ($searchFields) {
//                            $q->where('status', 'like', '%' . $searchFields['status'] . '%');
//                        });
//                });
//            })
//            ->get()
//            ->makeHidden(['id','account','password','email','company_star_certificate_address', 'competition_star_certificate_address','research_star_certificate_address','student_id','updated_at','created_at',]); // 隐藏不需要的字段
//    }

//    public static function searchStudents($searchFields, $teacherMajor)
//    {
//        $students = self::with([
//            'companyStars' => function ($query) {
//                // 不选择敏感字段
//                $query->select(['student_id', 'company_name', 'company_type', 'applicant_rank', 'registration_time', 'company_scale', 'materials', 'rejection_reason', 'status']);
//            },
//            'competitionStars' => function ($query) {
//                // 不选择敏感字段
//                $query->select(['student_id', 'competition_name', 'registration_time', 'materials', 'rejection_reason', 'status']);
//            },
//            'researchStars' => function ($query) {
//                // 不选择敏感字段
//                $query->select(['student_id', 'project_name', 'project_level', 'ranking_total', 'approval_time', 'materials', 'rejection_reason', 'status']);
//            },
//            'paperStars' => function ($query) {
//                // 不选择敏感字段
//                $query->select(['student_id', 'journal_name', 'paper_title', 'ranking_total', 'publication_time', 'materials', 'rejection_reason', 'status']);
//            },
//            'softwareStars' => function ($query) {
//                // 不选择敏感字段
//                $query->select(['student_id', 'software_name', 'issuing_unit', 'ranking_total', 'approval_time', 'materials', 'rejection_reason', 'status']);
//            }
//        ])
//            // 添加专业限制，确保只能搜索与教师专业匹配的学生
//            ->where('major', $teacherMajor)
//
//            ->when(!empty($searchFields['name']), function ($query) use ($searchFields) {
//                $query->where('name', 'like', '%' . $searchFields['name'] . '%');
//            })
//            ->when(!empty($searchFields['major']), function ($query) use ($searchFields) {
//                $query->where('major', 'like', '%' . $searchFields['major'] . '%');
//            })
//            ->when(!empty($searchFields['c_name']), function ($query) use ($searchFields) {
//                $query->where(function ($subQuery) use ($searchFields) {
//                    $subQuery->whereHas('competitionStars', function ($q) use ($searchFields) {
//                        $q->where('competition_name', 'like', '%' . $searchFields['c_name'] . '%');
//                    })
//                        ->orWhereHas('companyStars', function ($q) use ($searchFields) {
//                            $q->where('company_name', 'like', '%' . $searchFields['c_name'] . '%');
//                        })
//                        ->orWhereHas('researchStars', function ($q) use ($searchFields) {
//                            $q->where('project_name', 'like', '%' . $searchFields['c_name'] . '%');
//                        })
//                        ->orWhereHas('paperStars', function ($q) use ($searchFields) {
//                            $q->where('paper_title', 'like', '%' . $searchFields['c_name'] . '%');
//                        })
//                        ->orWhereHas('softwareStars', function ($q) use ($searchFields) {
//                            $q->where('software_name', 'like', '%' . $searchFields['c_name'] . '%');
//                        });
//                });
//            })
//            ->when(!empty($searchFields['status']), function ($query) use ($searchFields) {
//                $query->where(function ($subQuery) use ($searchFields) {
//                    $subQuery->whereHas('competitionStars', function ($q) use ($searchFields) {
//                        $q->where('status', 'like', '%' . $searchFields['status'] . '%');
//                    })
//                        ->orWhereHas('companyStars', function ($q) use ($searchFields) {
//                            $q->where('status', 'like', '%' . $searchFields['status'] . '%');
//                        })
//                        ->orWhereHas('researchStars', function ($q) use ($searchFields) {
//                            $q->where('status', 'like', '%' . $searchFields['status'] . '%');
//                        })
//                        ->orWhereHas('paperStars', function ($q) use ($searchFields) {
//                            $q->where('status', 'like', '%' . $searchFields['status'] . '%');
//                        })
//                        ->orWhereHas('softwareStars', function ($q) use ($searchFields) {
//                            $q->where('status', 'like', '%' . $searchFields['status'] . '%');
//                        });
//                });
//            })
//            ->get()
//            ->toArray();
//
//        // 手动删除不需要的字段
//        return array_map(function ($student) {
//            unset($student['id'], $student['created_at'], $student['updated_at'], $student['account'], $student['password'], $student['email'], $student['company_star_certificate_address'], $student['competition_star_certificate_address'], $student['research_star_certificate_address']);
//
//            // 隐藏各星表中不需要的字段
//            if (isset($student['company_stars'])) {
//                $student['company_stars'] = array_map(function ($star) {
//                    unset($star['student_id'], $star['id'], $star['created_at'], $star['updated_at']);
//                    return $star;
//                }, $student['company_stars']);
//            }
//            if (isset($student['competition_stars'])) {
//                $student['competition_stars'] = array_map(function ($star) {
//                    unset($star['student_id'], $star['id'], $star['created_at'], $star['updated_at']);
//                    return $star;
//                }, $student['competition_stars']);
//            }
//            if (isset($student['research_stars'])) {
//                $student['research_stars'] = array_map(function ($star) {
//                    unset($star['student_id'], $star['id'], $star['created_at'], $star['updated_at']);
//                    return $star;
//                }, $student['research_stars']);
//            }
//            if (isset($student['paper_stars'])) {
//                $student['paper_stars'] = array_map(function ($star) {
//                    unset($star['student_id'], $star['id'], $star['created_at'], $star['updated_at']);
//                    return $star;
//                }, $student['paper_stars']);
//            }
//            if (isset($student['software_stars'])) {
//                $student['software_stars'] = array_map(function ($star) {
//                    unset($star['student_id'], $star['id'], $star['created_at'], $star['updated_at']);
//                    return $star;
//                }, $student['software_stars']);
//            }
//
//            return $student;
//        }, $students);
//    }

//    public static function searchStudents($searchFields, $teacherMajor)
//    {
//        $students = self::with([
//            'companyStars' => function ($query) {
//                // 不选择敏感字段
//                $query->select(['student_id', 'company_name', 'company_type','applicant_rank','registration_time','company_scale','materials','rejection_reason','status']);
//            },
//            'competitionStars' => function ($query) {
//                $query->select(['student_id', 'competition_name', 'registration_time','materials','rejection_reason','status']);
//            },
//            'researchStars' => function ($query) {
//                $query->select(['student_id', 'project_name', 'project_level','ranking_total','approval_time','materials','rejection_reason','status']);
//            },
//            'paperStars' => function ($query) {
//                $query->select(['student_id', 'journal_name','paper_title', 'ranking_total','publication_time','materials','rejection_reason','status']);
//            },
//            'softwareStars' => function ($query) {
//                $query->select(['student_id', 'software_name', 'issuing_unit','ranking_total','approval_time','materials','rejection_reason','status']);
//            }
//        ])
//            ->when(!empty($searchFields['name']), function ($query) use ($searchFields) {
//                $query->where('name', 'like', '%' . $searchFields['name'] . '%');
//            })
//            ->when(!empty($searchFields['major']), function ($query) use ($searchFields) {
//                $query->where('major', 'like', '%' . $searchFields['major'] . '%');
//            })
//            ->when(!empty($searchFields['c_name']), function ($query) use ($searchFields) {
//                $query->where(function ($subQuery) use ($searchFields) {
//                    $subQuery->whereHas('competitionStars', function ($q) use ($searchFields) {
//                        $q->where('competition_name', 'like', '%' . $searchFields['c_name'] . '%');
//                    })
//                        ->orWhereHas('companyStars', function ($q) use ($searchFields) {
//                            $q->where('company_name', 'like', '%' . $searchFields['c_name'] . '%');
//                        })
//                        ->orWhereHas('researchStars', function ($q) use ($searchFields) {
//                            $q->where('project_name', 'like', '%' . $searchFields['c_name'] . '%');
//                        })
//                        ->orWhereHas('paperStars', function ($q) use ($searchFields) {
//                            $q->where('paper_title', 'like', '%' . $searchFields['c_name'] . '%');
//                        })
//                        ->orWhereHas('softwareStars', function ($q) use ($searchFields) {
//                            $q->where('software_name', 'like', '%' . $searchFields['c_name'] . '%');
//                        });
//                });
//            })
//            ->when(!empty($searchFields['status']), function ($query) use ($searchFields) {
//                $query->where(function ($subQuery) use ($searchFields) {
//                    $subQuery->whereHas('competitionStars', function ($q) use ($searchFields) {
//                        $q->where('status', 'like', '%' . $searchFields['status'] . '%');
//                    })
//                        ->orWhereHas('companyStars', function ($q) use ($searchFields) {
//                            $q->where('status', 'like', '%' . $searchFields['status'] . '%');
//                        })
//                        ->orWhereHas('researchStars', function ($q) use ($searchFields) {
//                            $q->where('status', 'like', '%' . $searchFields['status'] . '%');
//                        })
//                        ->orWhereHas('paperStars', function ($q) use ($searchFields) {
//                            $q->where('status', 'like', '%' . $searchFields['status'] . '%');
//                        })
//                        ->orWhereHas('softwareStars', function ($q) use ($searchFields) {
//                            $q->where('status', 'like', '%' . $searchFields['status'] . '%');
//                        });
//                });
//            })
//            // 过滤当前管理员的专业
//            ->where('major', $teacherMajor)
//            ->get()
//            ->toArray();
//
//        // 处理数据
//        return array_map(function ($student) {
//            unset($student['id'], $student['created_at'], $student['updated_at']);
//            // 省略其他隐藏字段的处理逻辑
//            return $student;
//        }, $students);
//    }

    public static function searchStudents($searchFields, $teacherMajor)
    {
        $students = self::with([
            'companyStars' => function ($query) {
                $query->select(['student_id', 'company_name', 'company_type', 'applicant_rank', 'registration_time', 'company_scale', 'materials', 'rejection_reason', 'status']);
            },
            'competitionStars' => function ($query) {
                $query->select(['student_id', 'competition_name', 'registration_time', 'materials', 'rejection_reason', 'status']);
            },
            'researchStars' => function ($query) {
                $query->select(['student_id', 'project_name', 'project_level', 'ranking_total', 'approval_time', 'materials', 'rejection_reason', 'status']);
            },
            'paperStars' => function ($query) {
                $query->select(['student_id', 'journal_name', 'paper_title', 'ranking_total', 'publication_time', 'materials', 'rejection_reason', 'status']);
            },
            'softwareStars' => function ($query) {
                $query->select(['student_id', 'software_name', 'issuing_unit', 'ranking_total', 'approval_time', 'materials', 'rejection_reason', 'status']);
            }
        ])
            ->when(!empty($searchFields['name']), function ($query) use ($searchFields) {
                $query->where('name', 'like', '%' . $searchFields['name'] . '%');
            })
            ->when(!empty($searchFields['major']), function ($query) use ($searchFields) {
                $query->where('major', 'like', '%' . $searchFields['major'] . '%');
            })
            ->when(!empty($searchFields['c_name']), function ($query) use ($searchFields) {
                $query->where(function ($subQuery) use ($searchFields) {
                    $subQuery->whereHas('competitionStars', function ($q) use ($searchFields) {
                        $q->where('competition_name', 'like', '%' . $searchFields['c_name'] . '%');
                    })
                        ->orWhereHas('companyStars', function ($q) use ($searchFields) {
                            $q->where('company_name', 'like', '%' . $searchFields['c_name'] . '%');
                        })
                        ->orWhereHas('researchStars', function ($q) use ($searchFields) {
                            $q->where('project_name', 'like', '%' . $searchFields['c_name'] . '%');
                        })
                        ->orWhereHas('paperStars', function ($q) use ($searchFields) {
                            $q->where('paper_title', 'like', '%' . $searchFields['c_name'] . '%');
                        })
                        ->orWhereHas('softwareStars', function ($q) use ($searchFields) {
                            $q->where('software_name', 'like', '%' . $searchFields['c_name'] . '%');
                        });
                });
            })
            ->when(!empty($searchFields['status']), function ($query) use ($searchFields) {
                $query->where(function ($subQuery) use ($searchFields) {
                    $subQuery->whereHas('competitionStars', function ($q) use ($searchFields) {
                        $q->where('status', 'like', '%' . $searchFields['status'] . '%');
                    })
                        ->orWhereHas('companyStars', function ($q) use ($searchFields) {
                            $q->where('status', 'like', '%' . $searchFields['status'] . '%');
                        })
                        ->orWhereHas('researchStars', function ($q) use ($searchFields) {
                            $q->where('status', 'like', '%' . $searchFields['status'] . '%');
                        })
                        ->orWhereHas('paperStars', function ($q) use ($searchFields) {
                            $q->where('status', 'like', '%' . $searchFields['status'] . '%');
                        })
                        ->orWhereHas('softwareStars', function ($q) use ($searchFields) {
                            $q->where('status', 'like', '%' . $searchFields['status'] . '%');
                        });
                });
            })
            ->where('major', $teacherMajor)
            ->get()
            ->toArray();
        //dd($students);
        // 检查用户是否输入了 c_name，如果没有则设置为空字符串
        $p['c_name'] = isset($searchFields['c_name']) ? $searchFields['c_name'] : '';
        $p['status'] = isset($searchFields['status']) ? $searchFields['status'] : '';

        return array_map(function ($student) use ($p) {
            // 移除不必要的字段
            unset($student['id'], $student['created_at'], $student['updated_at'],$student['student_id']);
            $filterKeyword = $p['c_name'];

            // 过滤掉 competitionStars 中不包含关键词的项目
            if (isset($student['competition_stars'])) {
                $student['competition_stars'] = array_filter($student['competition_stars'], function ($competition) use ($filterKeyword, $p) {
                    return (empty($filterKeyword) || strpos($competition['competition_name'], $filterKeyword) !== false) &&
                        (empty($p['status']) || strpos($competition['status'], $p['status']) !== false);
                });

                // 遍历并移除每个 competition_star 项中的 student_id
                foreach ($student['competition_stars'] as &$competitionStar) {
                    unset($competitionStar['student_id']);
                }

                if (empty($student['competition_stars'])) {
                    unset($student['competition_stars']);
                }
            }

            // 过滤掉 companyStars 中不包含关键词的项目
            if (isset($student['company_stars'])) {
                $student['company_stars'] = array_filter($student['company_stars'], function ($company) use ($filterKeyword, $p) {
                    return (empty($filterKeyword) || strpos($company['company_name'], $filterKeyword) !== false) &&
                        (empty($p['status']) || strpos($company['status'], $p['status']) !== false);
                });

                foreach ($student['company_stars'] as &$companyStar) {
                    unset($companyStar['student_id']);
                }

                if (empty($student['company_stars'])) {
                    unset($student['company_stars']);
                }
            }

            // 过滤掉 researchStars 中不包含关键词的项目
            if (isset($student['research_stars'])) {
                $student['research_stars'] = array_filter($student['research_stars'], function ($research) use ($filterKeyword, $p) {
                    return (empty($filterKeyword) || strpos($research['project_name'], $filterKeyword) !== false) &&
                        (empty($p['status']) || strpos($research['status'], $p['status']) !== false);
                });

                foreach ($student['research_stars'] as &$researchStar) {
                    unset($researchStar['student_id']);
                }

                if (empty($student['research_stars'])) {
                    unset($student['research_stars']);
                }
            }

            // 过滤掉 paperStars 中不包含关键词的项目
            if (isset($student['paper_stars'])) {
                $student['paper_stars'] = array_filter($student['paper_stars'], function ($paper) use ($filterKeyword, $p) {
                    return (empty($filterKeyword) || strpos($paper['paper_title'], $filterKeyword) !== false) &&
                        (empty($p['status']) || strpos($paper['status'], $p['status']) !== false);
                });

                foreach ($student['paper_stars'] as &$paperStar) {
                    unset($paperStar['student_id']);
                }

                if (empty($student['paper_stars'])) {
                    unset($student['paper_stars']);
                }
            }

            // 过滤掉 softwareStars 中不包含关键词的项目
            if (isset($student['software_stars'])) {
                $student['software_stars'] = array_filter($student['software_stars'], function ($software) use ($filterKeyword, $p) {
                    return (empty($filterKeyword) || strpos($software['software_name'], $filterKeyword) !== false) &&
                        (empty($p['status']) || strpos($software['status'], $p['status']) !== false);
                });

                foreach ($student['software_stars'] as &$softwareStar) {
                    unset($softwareStar['student_id']);
                }

                if (empty($student['software_stars'])) {
                    unset($student['software_stars']);
                }
            }

            return $student;
        }, $students);
    }
}


