<?php

namespace Modules\Auth\Http\Controllers;

use App\Helpers\ApiResponse\ApiResponseHelper;
use App\Helpers\ApiResponse\Result;
use App\Helpers\ApiResponse\SuccessResult;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Modules\Auth\Actions\ForgetPasswordAction;
use Modules\Auth\Actions\LoginAction;
use Modules\Auth\Actions\LogoutAction;
use Modules\Auth\Actions\RegisterAction;
use Modules\Auth\Actions\ResetPasswordAction;
use Modules\Auth\DTO\LoginUserDTO;
use Modules\Auth\DTO\RegisterUserDTO;
use Modules\Auth\DTO\ResetPasswordUserDTO;
use Modules\Auth\Exceptions\PasswordResetException;
use Modules\Auth\Http\Requests\ForgetPasswordUserRequest;
use Modules\Auth\Http\Requests\LoginUserRequest;
use Modules\Auth\Http\Requests\RegisterUserRequest;
use Modules\Auth\Http\Requests\ResetPasswordUserRequest;
use Modules\Auth\Http\Resources\LoginUserResource;

class AuthController extends Controller
{
    public function __construct() {}

    /**
     * @OA\Post(
     *     path="/api/v1/auth/login",
     *     operationId="loginUser",
     *     tags={"Authentication"},
     *     summary="Login a user",
     *     description="Handles user login",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="P@ssword123")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *
     *         @OA\JsonContent(ref="#/components/schemas/LoginUserResource")
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="The given data was invalid.")
     *         )
     *     )
     * )
     *
     * @throws ValidationException
     */
    public function login(LoginUserRequest $request, LoginAction $loginAction): JsonResponse
    {
        /**
         * @var array{
         *   email:string,
         *   password:string
         * } $dataFromRequest
         */
        $dataFromRequest = $request->validated();
        $loginDTO = LoginUserDTO::fromRequest($dataFromRequest);

        $loggedUser = $loginAction->handle($loginDTO);

        return ApiResponseHelper::sendResponse(new Result(LoginUserResource::make($loggedUser)));
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/register",
     *     operationId="registerUser",
     *     tags={"Authentication"},
     *     summary="Register a new user",
     *     description="Handles the registration of a new user, validating the input data",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             type="object",
     *             required={"name", "email", "password", "password_confirmation"},
     *
     *             @OA\Property(property="name", type="string", example="John Doe", description="The name of the user"),
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com", description="The email address of the user"),
     *             @OA\Property(property="password", type="string", format="password", example="P@ssword123", description="The password for the user, must meet security requirements"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="P@ssword123", description="Password confirmation")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Registration successful",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Registration successful")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *
     *                 @OA\AdditionalProperties(
     *                     type="array",
     *
     *                     @OA\Items(type="string", example="The name field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="An error occurred during registration.")
     *         )
     *     )
     * )
     */
    public function register(RegisterUserRequest $request, RegisterAction $registerAction): JsonResponse
    {
        /**
         * @var array{
         *   name:string,
         *   email:string,
         *   password:string,
         *   password_confirmation:string
         * } $dataFromRequest
         */
        $dataFromRequest = $request->validated();
        $registerDTO = RegisterUserDTO::fromRequest($dataFromRequest);

        $registerAction->handle($registerDTO);

        return ApiResponseHelper::sendSuccessResponse(new SuccessResult('Registration successful'));
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/logout",
     *     operationId="logoutUser",
     *     tags={"Authentication"},
     *     summary="Logout a user",
     *     description="Logs out the authenticated user by invalidating their token.",
     *     security={
     *         {"sanctum": {}}
     *     },
     *
     *     @OA\Response(
     *         response=200,
     *         description="Logout successful",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Logout successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function logout(LogoutAction $logoutAction): JsonResponse
    {
        $logoutAction->handle();

        return ApiResponseHelper::sendSuccessResponse(new SuccessResult('Logout successfully'));
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/forget_password",
     *     operationId="sendResetLinkEmail",
     *     tags={"Authentication"},
     *     summary="Send password reset link",
     *     description="Send a password reset link to the user's email",
     *     security={
     *          {"sanctum": {}}
     *      },
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             type="object",
     *             required={"email"},
     *
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Password reset email sent successfully",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="message", type="string", example="An Email with token sent successfully")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="email", type="array", @OA\Items(type="string", example="The email field is required."))
     *             )
     *         )
     *     )
     * )
     */
    public function sendResetLinkEmail(ForgetPasswordUserRequest $request,
        ForgetPasswordAction $forgetPasswordAction): JsonResponse
    {
        /**
         * @var array{
         *   email:string
         * } $dataFromRequest
         */
        $dataFromRequest = $request->validated();

        $forgetPasswordAction->handle($dataFromRequest['email']);

        //Todo please handle the password reset status and make a response
        return ApiResponseHelper::sendSuccessResponse(new SuccessResult('An Email with token sent successfully'));
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/reset_password",
     *     operationId="resetPassword",
     *     tags={"Authentication"},
     *     summary="Reset password",
     *     description="Resets the password for the user using the reset token sent via email",
     *     security={
     *          {"sanctum": {}}
     *      },
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             type="object",
     *             required={"email", "token", "password", "password_confirmation"},
     *
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="token", type="string", example="reset-token-string"),
     *             @OA\Property(property="password", type="string", format="password", example="newP@ssword123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="newP@ssword123")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Password reset successfully",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="message", type="string", example="Password Reset successfully")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error or invalid token",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="The provided token is invalid."),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="email", type="array", @OA\Items(type="string", example="The email field is required.")),
     *                 @OA\Property(property="password", type="array", @OA\Items(type="string", example="The password must be at least 8 characters.")),
     *                 @OA\Property(property="token", type="array", @OA\Items(type="string", example="The provided token is invalid."))
     *             )
     *         )
     *     )
     * )
     *
     * @throws PasswordResetException
     */
    public function resetPassword(ResetPasswordUserRequest $request, ResetPasswordAction $passwordAction): JsonResponse
    {

        /**
         * @var array{
         *   email:string,
         *   token:string,
         *   password:string,
         *   password_confirmation:string
         * } $dataFromRequest
         */
        $dataFromRequest = $request->validated();

        $responseMsg = $passwordAction->handle(ResetPasswordUserDTO::fromRequest($dataFromRequest));

        return ApiResponseHelper::sendSuccessResponse(new SuccessResult($responseMsg));
    }
}
